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
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Slack\Developer;
use TomPHP\TimeTracker\Slack\Project;
use TomPHP\TimeTracker\Slack\SlackMessenger;
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

    /** @var SlackMessenger */
    private $messenger;

    public function __construct()
    {
        $this->prophet     = new Prophet();
        $this->services    = new Container();

        Configurator::apply()
            ->configFromFile(__DIR__ . '/../../../config/slack.global.php')
            ->withSetting(Configurator::SETTING_DEFAULT_SINGLETON_SERVICES, true)
            ->to($this->services);

        $this->timeTracker = $this->prophet->prophesize(TimeTracker::class);
        $this->messenger   = $this->prophet->prophesize(SlackMessenger::class);

        $this->services[TimeTracker::class]    = $this->timeTracker->reveal();
        $this->services[SlackMessenger::class] = $this->messenger->reveal();
    }

    /**
     * @Transform :developer
     */
    public function fetchDeveloperByName(string $name) : Developer
    {
        return $this->developers[$name];
    }

    /**
     * @Transform :project
     */
    public function fetchProjectByName(string $name) : Project
    {
        return $this->projects[$name];
    }

    /**
     * @Given :developerName is a developer with Slack handle @:slackHandle
     */
    public function createDeveloper(string $developerName, SlackHandle $slackHandle)
    {
        $developer = new Developer("developer-id-$developerName", $developerName, $slackHandle);

        $this->developers[$developerName] = $developer;

        $this->timeTracker
            ->fetchDeveloperBySlackHandle($slackHandle)
            ->willReturn($developer);
    }

    /**
     * @Given there is a project named :projectName
     */
    public function createProject(string $projectName)
    {
        $project = new Project("project-id-$projectName", $projectName);

        $this->projects[$projectName] = $project;

        $this->timeTracker
            ->fetchProjectByName($projectName)
            ->willReturn($project);
    }

    /**
     * @When :developer issues the command :command
     */
    public function developerIssuesCommand(Developer $developer, string $command)
    {
        $this->timeTracker->logTimeEntry(Argument::cetera())->willReturn();

        $this->commandRunner()->run($developer->slackHandle(), $command);
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
            $this->today(),
            $period,
            $description
        )->shouldHaveBeenCalled();
    }

    /**
     * @Then message saying :message should have been sent to Slack
     */
    public function messageSayingShouldHaveBeenSentToSlack(string $message)
    {
        $this->messenger->send($message)->shouldHaveBeenCalled();
    }

    private function today() : Date
    {
        return new Date();
    }

    private function commandRunner() : CommandRunner
    {
        return $this->services->get(CommandRunner::class);
    }
}
