<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack\Command;

use Prophecy\Argument;
use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Slack\Command\LogCommand;
use TomPHP\TimeTracker\Slack\Command\LogCommandHandler;
use TomPHP\TimeTracker\Slack\Developer;
use TomPHP\TimeTracker\Slack\Project;
use TomPHP\TimeTracker\Slack\SlackMessenger;
use TomPHP\TimeTracker\Slack\TimeTracker;

final class LogCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    const PROJECT_NAME = 'example project';
    const DATE         = '2016-09-24';
    const PERIOD       = 2;
    const DESCRIPTION  = 'Work on the Slack integration';

    /** @var LogCommandHandler */
    private $subject;

    /** @var TimeTracker */
    private $timeTracker;

    /** @var SlackMessenger */
    private $messenger;

    /** @var LogCommand */
    private $command;

    /** @var Developer */
    private $developer;

    /** @var Project */
    private $project;

    protected function setUp()
    {
        $this->timeTracker = $this->prophesize(TimeTracker::class);
        $this->messenger   = $this->prophesize(SlackMessenger::class);
        $this->developer   = new Developer(
            'dev-id',
            'dev-name',
            SlackHandle::fromString('dev-slack-handle')
        );
        $this->project     = new Project('project-id', 'project-name');

        $this->timeTracker->fetchDeveloperBySlackHandle(Argument::any())->willReturn($this->developer);
        $this->timeTracker->fetchProjectByName(Argument::any())->willReturn($this->project);
        $this->timeTracker->logTimeEntry(Argument::cetera())->willReturn();

        $this->command = new LogCommand(
            self::PROJECT_NAME,
            Date::fromString(self::DATE),
            Period::fromHours(self::PERIOD),
            self::DESCRIPTION
        );

        $this->subject = new LogCommandHandler($this->timeTracker->reveal(), $this->messenger->reveal());
    }

    /** @test */
    public function it_fetches_the_developer_by_slack_handle()
    {
        $this->subject->handle(SlackHandle::fromString('tom'), $this->command);

        $this->timeTracker
            ->fetchDeveloperBySlackHandle(SlackHandle::fromString('tom'))
            ->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_fetches_the_project_by_name()
    {
        $this->subject->handle(SlackHandle::fromString('tom'), $this->command);

        $this->timeTracker
            ->fetchProjectByName(self::PROJECT_NAME)
            ->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_logs_the_time_entry_with_the_time_tracker()
    {
        $this->subject->handle(SlackHandle::fromString('tom'), $this->command);

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
        $this->subject->handle(SlackHandle::fromString('tom'), $this->command);

        $this->messenger
            ->send('dev-name logged 2:00 hours against project-name')
            ->shouldHaveBeenCalled();
    }
}
