<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack\Command;

use Prophecy\Argument;
use test\support\TestUsers\Mike;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Slack\Command\LinkCommand;
use TomPHP\TimeTracker\Slack\Command\LinkCommandHandler;
use TomPHP\TimeTracker\Slack\Developer;
use TomPHP\TimeTracker\Slack\LinkedAccount;
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\TimeTracker;

final class LinkCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TimeTracker */
    private $timeTracker;

    /** @var LinkCommand */
    private $command;

    protected function setUp()
    {
        $this->timeTracker    = $this->prophesize(TimeTracker::class);
        $this->linkedAccounts = $this->prophesize(LinkedAccounts::class);

        $this->linkedAccounts->hasDeveloper(Argument::any())->willReturn(false);
        $this->linkedAccounts->hasSlackUser(Argument::any())->willReturn(false);
        $this->linkedAccounts->add(Argument::any())->willReturn();

        $this->timeTracker
            ->fetchDeveloperByEmail(Argument::any())
            ->willReturn(new Developer(Mike::id(), Mike::name()));

        $this->command = new LinkCommand(Mike::email());

        $this->subject = new LinkCommandHandler(
            $this->timeTracker->reveal(),
            $this->linkedAccounts->reveal()
        );
    }

    /** @test */
    public function it_fetches_the_developer_by_email()
    {
        $this->subject->handle(Mike::slackUserId(), $this->command);

        $this->timeTracker
            ->fetchDeveloperByEmail(Mike::email())
            ->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_checks_if_the_slack_account_is_already_linked()
    {
        $this->subject->handle(Mike::slackUserId(), $this->command);

        $this->linkedAccounts->hasSlackUser(Mike::slackUserId())->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_returns_an_error_if_the_slack_account_is_already_linked()
    {
        $this->linkedAccounts->hasSlackUser(Argument::any())->willReturn(true);

        $result = $this->subject->handle(Mike::slackUserId(), $this->command);

        assertSame('ephemeral', $result['response_type']);
        assertSame('ERROR: Your account has already been linked.', $result['text']);
    }

    /** @test */
    public function it_checks_if_the_account_is_already_linked()
    {
        $this->markTestIncomplete('Not implementing this feature yet');
        $this->linkedAccounts->hasDeveloper(Argument::any())->willReturn(true);
        $this->subject->handle(Mike::slackUserId(), $this->command);

        $this->linkedAccounts->hasDeveloper(Mike::id())->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_returns_an_error_message_if_the_linked_account_is_found()
    {
        $this->markTestIncomplete('Not implementing this feature yet');
        $this->linkedAccounts->hasDeveloper(Argument::any())->willReturn(true);

        $result = $this->subject->handle(Mike::slackUserId(), $this->command);

        assertSame('ephemeral', $result['response_type']);
        assertSame('ERROR: Your account has already been linked.', $result['text']);
    }

    /** @test */
    public function it_stores_the_linked_account()
    {
        $result = $this->subject->handle(Mike::slackUserId(), $this->command);

        $this->linkedAccounts->add(new LinkedAccount(Mike::id(), Mike::slackUserId()))
            ->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_returns_a_success_message()
    {
        $result = $this->subject->handle(Mike::slackUserId(), $this->command);

        assertSame('ephemeral', $result['response_type']);
        assertSame('Hi Mike, your account has been successfully linked.', $result['text']);
    }
}
