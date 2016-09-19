<?php

namespace test\features\TomPHP\TimeTracker;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use TomPHP\TimeTracker\Domain\ProjectId;
use TomPHP\TimeTracker\Domain\Project;
use TomPHP\TimeTracker\Domain\User;
use TomPHP\TimeTracker\Domain\UserId;
use TomPHP\TimeTracker\Domain\Period;
use TomPHP\TimeTracker\Domain\Date;
use TomPHP\TimeTracker\Domain\TimeEntry;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /** @var UserId[] */
    private $users = [];

    /** @var ProjectId */
    private $projects = [];

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Transform :user
     */
    public function castUsernameToUserId(string $username) : UserId
    {
        if (!isset($this->users[$username])) {
            $userId = UserId::generate();
            $user = User::create($userId, $username);

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
}
