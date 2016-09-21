<?php declare(strict_types=1);

namespace test\features\TomPHP\TimeTracker;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use Pimple\Container;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\TimeTracker\Domain\Date;
use TomPHP\TimeTracker\Domain\EventBus;
use TomPHP\TimeTracker\Domain\EventHandlers\ProjectProjectionHandler;
use TomPHP\TimeTracker\Domain\Period;
use TomPHP\TimeTracker\Domain\Project;
use TomPHP\TimeTracker\Domain\ProjectId;
use TomPHP\TimeTracker\Domain\TimeEntry;
use TomPHP\TimeTracker\Domain\User;
use TomPHP\TimeTracker\Domain\UserId;
use TomPHP\TimeTracker\Storage\MemoryProjectProjections;
use TomPHP\Transform as T;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
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

        $config = [
            'event_handlers' => [
                ProjectProjectionHandler::class,
            ],
            'di' => [
                'services' => [
                    ProjectProjections::class => [
                        'class' => MemoryProjectProjections::class,
                    ],
                    ProjectProjectionHandler::class => [
                        'arguments' => [ProjectProjections::class],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->withSetting(Configurator::SETTING_DEFAULT_SINGLETON_SERVICES, true)
            ->to($this->services);

        foreach ($this->services['config.event_handlers'] as $name) {
            EventBus::subscribe($this->services[$name]);
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
     * @Given there is a project named :name
     */
    public function thereIsAProjectNamed(string $name)
    {
        $projectId = ProjectId::generate();

        Project::create($projectId, $name);
        $this->projects[$name] = $projectId;
    }

    /**
     * @When :user logs a time entry for :period hours on :date against :project with description :description
     */
    public function logsATimeEntryForHoursOnAgainstWithDescription(
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
    public function shouldHaveConfirmationThatHisTimeWasLogged($user)
    {
        // Intentionally blank
    }

    /**
     * @When I retrieve a list of all active projects
     */
    public function iRetrieveAListOfAllActiveProjects()
    {
        $this->result = $this->services[ProjectProjections::class]->all();
    }

    /**
     * @Then I should get the following projects:
     */
    public function iShouldGetTheFollowingProjects(TableNode $table)
    {
        assertSame(
            $table->getColumn(0),
            array_map(T\callMethod('name'), $this->result)
        );
    }

    /**
     * @Given :user has logged a time entry for :period hours on :date against :project
     */
    public function hasLoggedATimeEntryForHoursOnAgainst(
        UserId $user,
        Period $period,
        Date $date,
        ProjectId $project
    ) {
        $this->logsATimeEntryForHoursOnAgainstWithDescription(
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
    public function iRetrieveTheDetailsFor(ProjectId $project)
    {
        $this->result = $this->services[ProjectProjections::class]->withId($project);
    }

    /**
     * @Then I should see that the total hours spent on the project is :period
     */
    public function iShouldSeeThatTheTotalHoursSpentOnTheProjectIs(Period $period)
    {
        assertEquals($period, $this->result->totalTime());
    }
}
