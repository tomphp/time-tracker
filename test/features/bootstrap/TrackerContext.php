<?php declare(strict_types=1);

namespace test\features\TomPHP\TimeTracker;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use Pimple\Container;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\TimeTracker\Tracker\Date;
use TomPHP\TimeTracker\Tracker\EventBus;
use TomPHP\TimeTracker\Tracker\Period;
use TomPHP\TimeTracker\Tracker\Project;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\TimeEntry;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;
use TomPHP\TimeTracker\Tracker\User;
use TomPHP\TimeTracker\Tracker\UserId;
use TomPHP\Transform as T;

class TrackerContext implements Context, SnippetAcceptingContext
{
    /** @var UserId[] */
    private $users = [];

    /** @var ProjectId */
    private $projects = [];

    /** @var Pimple */
    private $services;

    public function __construct()
    {
        $this->services = new Container();

        Configurator::apply()
            ->configFromFile(__DIR__ . '/../../../config/tracker.config.php')
            ->withSetting(Configurator::SETTING_DEFAULT_SINGLETON_SERVICES, true)
            ->to($this->services);

        foreach ($this->services['config.event_handlers'] as $name) {
            EventBus::addHandler($this->services[$name]);
        }
    }

    /**
     * @Transform :user
     */
    public function castUsernameToUserId(string $username) : UserId
    {
        if (!isset($this->users[$username])) {
            $userId = UserId::generate();
            $user   = User::create($userId, $username);

            $this->users[$username] = $userId;
        }

        return $this->users[$username];
    }

    /**
     * @Transform :project
     */
    public function castProjectNameToProjectId(string $name) : ProjectId
    {
        return $this->projects[$name];
    }

    /**
     * @Transform :period
     */
    public function castStringToPeriod(string $string) : Period
    {
        return Period::fromString($string);
    }

    /**
     * @Transform :date
     */
    public function castStringToDate(string $string) : Date
    {
        return Date::fromString($string);
    }

    /**
     * @Transform table:user,date,time,description
     */
    public function castTimeEntryTableToArray(TableNode $table) : array
    {
        return array_map(
            function (array $entry) {
                return [
                    'user'        => $this->castUsernameToUserId($entry['user']),
                    'date'        => $this->castStringToDate($entry['date']),
                    'time'        => $this->castStringToPeriod($entry['time']),
                    'description' => $entry['description'],
                ];
            },
            $table->getHash()
        );
    }

    /**
     * @Given there is a project named :name
     */
    public function createProjectNamed(string $name)
    {
        $projectId = ProjectId::generate();

        Project::create($projectId, $name);
        $this->projects[$name] = $projectId;
    }

    /**
     * @When :user logs a time entry for :period hours on :date against :project with description :description
     */
    public function logTimeEntryWithDescription(
        UserId $user,
        Period $period,
        Date $date,
        ProjectId $project,
        string $description
    ) {
        TimeEntry::log($user, $project, $date, $period, $description);
    }

    /**
     * @Then :user should have confirmation that his time was logged
     */
    public function doNothing($user)
    {
        // Intentionally blank
    }

    /**
     * @When I retrieve a list of all active projects
     */
    public function fetchAllActiveProjectProjections()
    {
        $this->result = $this->services[ProjectProjections::class]->all();
    }

    /**
     * @Then I should get the following projects:
     */
    public function assertResultMatchesProjectTable(TableNode $table)
    {
        assertSame(
            $table->getColumn(0),
            array_map(T\callMethod('name'), $this->result)
        );
    }

    /**
     * @Given :user has logged a time entry for :period hours on :date against :project
     */
    public function logTimeEntry(
        UserId $user,
        Period $period,
        Date $date,
        ProjectId $project
    ) {
        $this->logTimeEntryWithDescription(
            $user,
            $period,
            $date,
            $project,
            'Example description'
        );
    }

    /**
     * @When I retrieve the details for :project
     */
    public function fetchProjectProjection(ProjectId $project)
    {
        $this->result = $this->services[ProjectProjections::class]->withId($project);
    }

    /**
     * @Then I should see that the total hours spent on the project is :period
     */
    public function assertProjectTotalTime(Period $period)
    {
        assertEquals($period, $this->result->totalTime());
    }

    /**
     * @Given the following time entries have been logged against :project:
     */
    public function logMultipleTimeEntries(ProjectId $project, array $entries)
    {
        foreach ($entries as $entry) {
            $this->logTimeEntryWithDescription(
                $entry['user'],
                $entry['time'],
                $entry['date'],
                $project,
                $entry['description']
            );
        }
    }

    /**
     * @When I retrieve the time entries for :project
     */
    public function fetchTimeEntriesForProject(ProjectId $project)
    {
        $this->result = $this->services[TimeEntryProjections::class]->forProject($project);
    }

    /**
     * @Then I should see these time entries:
     */
    public function assertTableContainsTimeEntryProjections(array $entries)
    {
        $results = array_map(
            function (TimeEntryProjection $entry) {
                return [
                    'user'        => $entry->userId(),
                    'date'        => $entry->date(),
                    'time'        => $entry->period(),
                    'description' => $entry->description(),
                ];
            },
            $this->result
        );

        assertEquals($entries, $results);
    }
}
