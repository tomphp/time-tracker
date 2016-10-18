<?php declare(strict_types=1);

namespace test\features\TomPHP\TimeTracker;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Interop\Container\ContainerInterface;
use Prophecy\Argument;
use Prophecy\Prophet;
use Slim\Container;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\DeveloperId;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Common\ProjectId;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Slack\Developer;
use TomPHP\TimeTracker\Slack\Project;
use TomPHP\TimeTracker\Slack\SlackUserId;
use TomPHP\TimeTracker\Slack\TimeTracker;

class SlackContext implements Context, SnippetAcceptingContext
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

    /** @var TimeTracker */
    private $timeTracker;

    /** @var SlackUserId[] */
    private $slackUsers = [];

    /** @var array */
    private $result;

    public function __construct()
    {
        $this->prophet  = new Prophet();
        $this->services = new Container();

        Configurator::apply()
            ->configFromFiles(__DIR__ . '/../../../config/*.global.php')
            ->configFromFiles(__DIR__ . '/../../../config/*.features.php')
            ->withSetting(Configurator::SETTING_DEFAULT_SINGLETON_SERVICES, true)
            ->to($this->services);

        $this->timeTracker = $this->prophet->prophesize(TimeTracker::class);

        $this->services[TimeTracker::class] = $this->timeTracker->reveal();
    }

    /**
     * @Transform
     */
    public function fetchDeveloperByName(string $name) : Developer
    {
        return $this->developers[$name];
    }

    /**
     * @Transform
     */
    public function fetchProjectByName(string $name) : Project
    {
        return $this->projects[$name];
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
        $id = DeveloperId::fromString("developer-id-$developerName");

        $developer = new Developer($id, $developerName);

        $this->developers[$developerName] = $developer;

        $this->timeTracker->fetchDeveloperByEmail($email)->willReturn($developer);

        $this->timeTracker->fetchDeveloperById($id)->willReturn($developer);
    }

    /**
     * @Given :developerName has linked her slack user to :email
     */
    public function linkSlackUser(SlackUserId $userId, Email $email)
    {
        $this->developerIssuesCommand($userId, "link to account $email");
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
     * @Given there is a project named :projectName
     */
    public function createProject(string $projectName)
    {
        $project = new Project(
            ProjectId::fromString("project-id-$projectName"),
            $projectName
        );

        $this->projects[$projectName] = $project;

        $this->timeTracker
            ->fetchProjectByName($projectName)
            ->willReturn($project);
    }

    /**
     * @Given :developer has already issued the command :command
     * @When :developer issues the command :command
     * @When :developer issues the command :command again
     */
    public function developerIssuesCommand(SlackUserId $userId, string $command)
    {
        $this->timeTracker->logTimeEntry(Argument::cetera())->willReturn();

        $this->result = $this->commandRunner()->run($userId, $command);
    }

    /**
     * @Then :period hours should have been logged today by :developer against :project for :description
     */
    public function assertTimeEntryLogged(
        Period $period,
        Developer $developer,
        Project $project,
        string $description
    ) {
        $this->timeTracker->logTimeEntry(
            $developer,
            $project,
            Date::today(),
            $period,
            $description
        )->shouldHaveBeenCalled();
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
}
