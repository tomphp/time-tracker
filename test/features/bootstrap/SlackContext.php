<?php declare(strict_types=1);

namespace test\features\TomPHP\TimeTracker;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use Pimple\Container;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\TimeTracker\Domain\Date;
use TomPHP\TimeTracker\Domain\EventBus;
use TomPHP\TimeTracker\Domain\EventHandlers\ProjectProjectionHandler;
use TomPHP\TimeTracker\Domain\EventHandlers\TimeEntryProjectionHandler;
use TomPHP\TimeTracker\Domain\Period;
use TomPHP\TimeTracker\Domain\Project;
use TomPHP\TimeTracker\Domain\ProjectId;
use TomPHP\TimeTracker\Domain\TimeEntry;
use TomPHP\TimeTracker\Domain\TimeEntryProjection;
use TomPHP\TimeTracker\Domain\TimeEntryProjections;
use TomPHP\TimeTracker\Domain\User;
use TomPHP\TimeTracker\Domain\UserId;
use TomPHP\TimeTracker\Storage\MemoryProjectProjections;
use TomPHP\TimeTracker\Storage\MemoryTimeEntryProjections;
use TomPHP\Transform as T;

class SlackContext implements Context, SnippetAcceptingContext
{

    /**
     * @Given Tom is a user
     */
    public function tomIsAUser()
    {
        throw new PendingException();
    }

    /**
     * @Given there is a project named :arg1
     */
    public function thereIsAProjectNamed($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When Tom issues the command :arg1
     */
    public function tomIssuesTheCommand($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then :arg1 hours should have been logged today against :arg2 for :arg3
     */
    public function hoursShouldHaveBeenLoggedTodayAgainstFor($arg1, $arg2, $arg3)
    {
        throw new PendingException();
    }

    /**
     * @Then message saying :arg1 should have been sent to Slack
     */
    public function messageSayingShouldHaveBeenSentToSlack($arg1)
    {
        throw new PendingException();
    }
}
