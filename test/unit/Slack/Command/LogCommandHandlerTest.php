<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack\Command;

use Prophecy\Argument;
use test\support\TestUsers\Fran;
use test\support\TestUsers\IngredientInventory;
use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Slack\Command\LogCommand;
use TomPHP\TimeTracker\Slack\Command\LogCommandHandler;
use TomPHP\TimeTracker\Slack\Developer;
use TomPHP\TimeTracker\Slack\LinkedAccount;
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\Project;
use TomPHP\TimeTracker\Slack\TimeTracker;

final class LogCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    const DATE         = '2016-09-24';
    const PERIOD       = 2;
    const DESCRIPTION  = 'Work on the Slack integration';

    /** @var LogCommandHandler */
    private $subject;

    /** @var LinkedAccounts */
    private $linkedAccounts;

    /** @var TimeTracker */
    private $timeTracker;

    /** @var LogCommand */
    private $command;

    /** @var Developer */
    private $developer;

    /** @var Project */
    private $project;

    protected function setUp()
    {
        $this->timeTracker    = $this->prophesize(TimeTracker::class);
        $this->linkedAccounts = $this->prophesize(LinkedAccounts::class);
        $this->developer      = new Developer(Fran::id(), Fran::name());
        $this->project        = IngredientInventory::asSlackProject();

        $this->linkedAccounts
            ->withSlackUserId(Argument::any())
            ->willReturn(new LinkedAccount(Fran::id(), Fran::slackUserId()));
        $this->linkedAccounts->hasSlackUser(Argument::any())->willReturn(true);

        $this->timeTracker->fetchDeveloperById(Argument::any())->willReturn($this->developer);
        $this->timeTracker->fetchProjectByName(Argument::any())->willReturn($this->project);
        $this->timeTracker->hasProjectWithName(Argument::any())->willReturn(true);
        $this->timeTracker->logTimeEntry(Argument::cetera())->willReturn();

        $this->command = new LogCommand(
            IngredientInventory::name(),
            Date::fromString(self::DATE),
            Period::fromHours(self::PERIOD),
            self::DESCRIPTION
        );

        $this->subject = new LogCommandHandler(
            $this->timeTracker->reveal(),
            $this->linkedAccounts->reveal()
        );
    }

    /** @test */
    public function it_fetches_the_linked_account_for_the_slack_user()
    {
        $this->subject->handle(Fran::slackUserId(), $this->command);

        $this->linkedAccounts->withSlackUserId(Fran::slackUserId())->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_fetches_the_linked_developer_account()
    {
        $this->subject->handle(Fran::slackUserId(), $this->command);

        $this->timeTracker->fetchDeveloperById(Fran::id())->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_returns_an_error_message_if_the_project_does_not_exist()
    {
        $this->timeTracker->hasProjectWithName('Unknown Project')->willReturn(false);

        $command = new LogCommand(
            'Unknown Project',
            Date::fromString(self::DATE),
            Period::fromHours(self::PERIOD),
            self::DESCRIPTION
        );

        $result = $this->subject->handle(Fran::slackUserId(), $command);

        assertSame('ephemeral', $result['response_type']);
        assertSame('Project Unknown Project was not found.', $result['text']);
    }

    /** @test */
    public function it_fetches_the_project_by_name()
    {
        $this->subject->handle(Fran::slackUserId(), $this->command);

        $this->timeTracker->fetchProjectByName(IngredientInventory::name())->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_logs_the_time_entry_with_the_time_tracker()
    {
        $this->subject->handle(Fran::slackUserId(), $this->command);

        $this->timeTracker
            ->logTimeEntry(
                $this->developer,
                $this->project,
                Date::fromString(self::DATE),
                Period::fromHours(self::PERIOD),
                self::DESCRIPTION
            )->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_sends_a_confirmation_message_to_slack()
    {
        $result = $this->subject->handle(Fran::slackUserId(), $this->command);

        assertSame('ephemeral', $result['response_type']);
        assertSame('Fran logged 2h against Ingredient Inventory', $result['text']);
    }
}
