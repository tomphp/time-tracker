<?php

namespace test\unit\TomPHP\TimeTracker\Slack\Command;

use Prophecy\Argument;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Slack\Command\LinkCommand;
use TomPHP\TimeTracker\Slack\Command\LinkCommandHandler;
use TomPHP\TimeTracker\Slack\Developer;
use TomPHP\TimeTracker\Slack\TimeTracker;

final class LinkCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TimeTracker */
    private $timeTracker;

    protected function setUp()
    {
        $this->timeTracker = $this->prophesize(TimeTracker::class);

        $this->timeTracker
            ->fetchDeveloperByEmail(Argument::any())
            ->willReturn(new Developer(
                'mike-developer-id',
                'Mike',
                SlackHandle::fromString('@mike')
            ));

        $this->subject = new LinkCommandHandler($this->timeTracker->reveal());
    }

    /** @test */
    public function on_handle_it_fetches_the_developer_by_email()
    {
        $command = new LinkCommand(Email::fromString('mike@rgsoftware.com'));

        $this->subject->handle(SlackHandle::fromString('mike'), $command);

        $this->timeTracker
            ->fetchDeveloperByEmail(Email::fromString('mike@rgsoftware.com'))
            ->shouldHaveBeenCalled();
    }

    /** @test */
    public function on_handle_it_returns_a_success_message()
    {
        $command = new LinkCommand(Email::fromString('mike@rgsoftware.com'));

        $result = $this->subject->handle(SlackHandle::fromString('mike'), $command);

        assertSame('ephemeral', $result['response_type']);
        assertSame('Hi Mike, your account has been successfully linked.', $result['text']);
    }
}
