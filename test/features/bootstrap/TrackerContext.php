<?php declare(strict_types=1);

namespace test\features\TomPHP\TimeTracker;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use Slim\Container;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\Developer;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\EventBus;
use TomPHP\TimeTracker\Tracker\Project;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\TimeEntry;
use TomPHP\TimeTracker\Tracker\TimeEntryId;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;
use TomPHP\Transform as T;

class TrackerContext implements Context, SnippetAcceptingContext
{
    use CommonTransforms;

    /** @var DeveloperId[] */
    private $developers = [];

    /** @var ProjectId */
    private $projects = [];

    /** @var Pimple */
    private $services;

    public function __construct()
    {
        $this->services = new Container();

        Configurator::apply()
            ->configFromFile(__DIR__ . '/../../../config/db.global.php')
            ->configFromFile(__DIR__ . '/../../../config/tracker.global.php')
            ->configFromFile(__DIR__ . '/../../../config/tracker.features.php')
            ->withSetting(Configurator::SETTING_DEFAULT_SINGLETON_SERVICES, true)
            ->to($this->services);

        EventBus::clearHandlers();
        foreach ($this->services['config.tracker.event_handlers'] as $name) {
            EventBus::addHandler($this->services[$name]);
        }
    }

    /**
     * @Transform
     */
    public function castDeveloperNameToDeveloperId(string $developerName) : DeveloperId
    {
        if (!isset($this->developers[$developerName])) {
            $developerId = DeveloperId::generate();
            Developer::create(
                $developerId,
                $developerName,
                Email::fromString('something@example.com'),
                SlackHandle::fromString(uniqid('@slack-'))
            );

            $this->developers[$developerName] = $developerId;
        }

        return $this->developers[$developerName];
    }

    /**
     * @Transform
     */
    public function castProjectNameToProjectId(string $name) : ProjectId
    {
        return $this->projects[$name];
    }

    /**
     * @Transform table:developer,date,time,description
     */
    public function castTimeEntryTableToArray(TableNode $table) : array
    {
        return array_map(
            function (array $entry) {
                return [
                    'developer'        => $this->castDeveloperNameToDeveloperId($entry['developer']),
                    'date'             => $this->castStringToDate($entry['date']),
                    'time'             => $this->castStringToPeriod($entry['time']),
                    'description'      => $entry['description'],
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
     * @When :developer logs a time entry for :period hours on :date against :project with description :description
     */
    public function logTimeEntryWithDescription(
        DeveloperId $developer,
        Period $period,
        Date $date,
        ProjectId $project,
        string $description
    ) {
        TimeEntry::log(
            TimeEntryId::generate(),
            $developer,
            $project,
            $date,
            $period,
            $description
        );
    }

    /**
     * @Then :developer should have confirmation that his time was logged
     */
    public function doNothing($developer)
    {
        // Intentionally blank
    }

    /**
     * @When :user retrieves a list of all active projects
     */
    public function fetchAllActiveProjectProjections()
    {
        $this->result = $this->services[ProjectProjections::class]->all();
    }

    /**
     * @Then she should get the following projects:
     */
    public function assertResultMatchesProjectTable(TableNode $table)
    {
        assertSame(
            $table->getColumn(0),
            array_map(T\callMethod('name'), $this->result)
        );
    }

    /**
     * @Given :developer has logged a time entry for :period hours on :date against :project
     */
    public function logTimeEntry(
        DeveloperId $developer,
        Period $period,
        Date $date,
        ProjectId $project
    ) {
        $this->logTimeEntryWithDescription(
            $developer,
            $period,
            $date,
            $project,
            'Example description'
        );
    }

    /**
     * @When :user retrieves the details for :project
     */
    public function fetchProjectProjection(ProjectId $project)
    {
        $this->result = $this->services[ProjectProjections::class]->withId($project);
    }

    /**
     * @Then she should see that the total hours spent on the project is :period
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
                $entry['developer'],
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
                    'developer'        => $entry->developerId(),
                    'date'             => $entry->date(),
                    'time'             => $entry->period(),
                    'description'      => $entry->description(),
                ];
            },
            $this->result
        );

        assertEquals($entries, $results);
    }
}
