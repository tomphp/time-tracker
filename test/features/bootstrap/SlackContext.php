<?php declare(strict_types=1);

namespace test\features\TomPHP\TimeTracker;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Prophecy\Prophet;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Slack\Date;
use TomPHP\TimeTracker\Slack\Developer;
use TomPHP\TimeTracker\Slack\Period;
use TomPHP\TimeTracker\Slack\Project;
use TomPHP\TimeTracker\Slack\TimeTracker;

class SlackContext implements Context, SnippetAcceptingContext
{
    /** @var Prophet */
    private $prophet;

    /** @var Developer[] */
    private $developers = [];

    /** @var Project[] */
    private $projects = [];

    /** @var TimeTracker */
    private $timeTracker;

    public function __construct()
    {
        $this->prophet     = new Prophet();
        $this->timeTracker = $this->prophet->prophesize(TimeTracker::class);
    }

    /**
     * @Transform :period
     */
    public function castStringToPeriod(string $string) : Period
    {
        return new Period();
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
    public function createDeveloper(string $developerName, string $slackHandle)
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
        $project = new Project("project-id-$projectName", $developerName);

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
        throw new PendingException();

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
    public function messageSayingShouldHaveBeenSentToSlack(Message $message)
    {
        throw new PendingException();

        $this->messenger()->send($message)->shouldHaveBeenCalled();
    }

    private function today() : Date
    {
        return new Date();
    }

    private function commandRunner() : CommandRunner
    {
        return new CommandRunner();
    }

    private function messenger() : SlackMessenger
    {
        return new SlackMessenger();
    }
}
