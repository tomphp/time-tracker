<?php declare(strict_types=1);

namespace test\features\TomPHP\TimeTracker;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Interop\Container\ContainerInterface;
use Prophecy\Prophet;
use Slim\Container;
use TomPHP\TimeTracker\Bootstrap;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Slack\SlackUserId;
use TomPHP\TimeTracker\Tracker\Developer;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
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

    /** @var SlackUserId[] */
    private $slackUsers = [];

    /** @var array */
    private $result;

    public function __construct()
    {
        $this->prophet  = new Prophet();
        $this->services = new Container();

        Bootstrap::run($this->services);
    }

    /**
     * @Transform
     */
    public function fetchDeveloperByName(string $name) : DeveloperId
    {
        $id =  $this->developers[$name]['id'];

        return $this->developerProjections()->withId($id)->id();
    }

    /**
     * @Transform
     */
    public function fetchProjectByName(string $name) : ProjectId
    {
        return $this->projectProjections()->withName($name)->id();
    }

    /**
     * @Transform
     */
    public function fetchDeveloperSlackUserId(string $developerName) : SlackUserId
    {
        return $this->slackUsers[$developerName];
    }

    /**
     * @Given :developerName has a developer account with email :email
     */
    public function createDeveloperWithEmail(string $developerName, Email $email)
    {
        $id = DeveloperId::generate();

        $this->developers[$developerName] = ['id' => $id];

        Developer::create($id, $developerName, $email);
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
     * @Given :developerName has a Slack account
     */
    public function createSlackUser(string $developerName)
    {
        $count = count($this->slackUsers);
        $id    = sprintf('U99999999%02f', $count);

        $this->slackUsers[$developerName] = SlackUserId::fromString($id);
    }

    /**
     * @Given :developerName has linked her slack user to :email
     */
    public function linkSlackUser(SlackUserId $userId, Email $email)
    {
        $this->issueSlackCommand($userId, "link to account $email");
    }

    /**
     * @Given :userId has already issued the command :commnad
     * @When :userId issues the command :command
     * @When :userId issues the command :command again
     */
    public function issueSlackCommand(SlackUserId $userId, string $command)
    {
        $this->result = $this->commandRunner()->run($userId, $command);
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
