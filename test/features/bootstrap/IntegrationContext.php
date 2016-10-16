<?php declare(strict_types=1);

namespace test\features\TomPHP\TimeTracker;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Interop\Container\ContainerInterface;
use Prophecy\Prophet;
use Slim\Container;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Slack\SlackMessenger;
use TomPHP\TimeTracker\Tracker\Developer;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\EventBus;
use TomPHP\TimeTracker\Tracker\Project;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

class IntegrationContext implements Context, SnippetAcceptingContext
{
    use CommonTransforms;

    /** @var Prophet */
    private $prophet;

    /** @var ContainerInterface */
    private $services;

    /** @var Developer[] */
    private $developers = [];

    /** @var Project[] */
    private $projects = [];

    /** @var array */
    private $result;

    /** @var SlackMessenger */
    private $messenger;

    public function __construct()
    {
        $this->prophet  = new Prophet();
        $this->services = new Container();

        Configurator::apply()
            ->configFromFiles(__DIR__ . '/../../../config/*.global.php')
            ->configFromFiles(__DIR__ . '/../../../config/*.features.php')
            ->withSetting(Configurator::SETTING_DEFAULT_SINGLETON_SERVICES, true)
            ->to($this->services);

        EventBus::clearHandlers();
        foreach ($this->services['config.tracker.event_handlers'] as $name) {
            EventBus::addHandler($this->services[$name]);
        }

        $this->messenger = $this->prophet->prophesize(SlackMessenger::class);

        $this->services[SlackMessenger::class] = $this->messenger->reveal();
    }

    /**
     * @Transform
     */
    public function fetchDeveloperByName(string $name) : DeveloperId
    {
        $slackHandle =  $this->developers[$name]['slack_handle'];

        return $this->developerProjections()->withSlackHandle($slackHandle)->id();
    }

    /**
     * @Transform
     */
    public function fetchProjectByName(string $name) : ProjectId
    {
        return $this->projectProjections()->withName($name)->id();
    }

    /**
     * @Given :developerName is a developer with Slack handle @:slackHandle
     */
    public function createDeveloper(string $developerName, SlackHandle $slackHandle)
    {
        $id = DeveloperId::generate();

        $this->developers[$developerName] = [
            'id'           => $id,
            'slack_handle' => $slackHandle,
        ];

        Developer::create(
            DeveloperId::generate(),
            $developerName,
            Email::fromString('something@example.com'),
            $slackHandle
        );
    }

    /**
     * @Given there is a project named :projectName
     */
    public function createProject(string $projectName)
    {
        $id = ProjectId::generate();

        $this->projects[$projectName] = ['id' => $id];

        $project = Project::create($id, $projectName);
    }

    /**
     * @When :developer issues the command :command
     */
    public function developerIssuesCommand(string $developer, string $command)
    {
        $this->result = $this->commandRunner()
            ->run($this->developers[$developer]['slack_handle'], $command);
    }

    /**
     * @Then :period hours should have been logged today by :developer against :project for :description
     */
    public function assertTimeEntryLogged(
        Period $period,
        DeveloperId $developerId,
        ProjectId $projectId,
        string $description
    ) {
        $entry = $this->timeEntryProjections()->forProject($projectId)[0];

        assertEquals($period, $entry->period());
        assertEquals($developerId, $entry->developerId());
        assertEquals($description, $entry->description());
    }

    /**
     * @Then :developerName should receive a response message saying :message
     */
    public function assertSlackResponseMessage(string $message)
    {
        assertSame('ephemeral', $this->result['response_type']);
        assertSame($message, $this->result['text']);
    }

    private function commandRunner() : CommandRunner
    {
        return $this->services->get(CommandRunner::class);
    }

    private function developerProjections() : DeveloperProjections
    {
        return $this->services->get(DeveloperProjections::class);
    }

    private function projectProjections() : ProjectProjections
    {
        return $this->services->get(ProjectProjections::class);
    }

    private function timeEntryProjections() : TimeEntryProjections
    {
        return $this->services->get(TimeEntryProjections::class);
    }
}
