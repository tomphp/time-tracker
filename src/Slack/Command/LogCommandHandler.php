<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\CommandHandler;
use TomPHP\TimeTracker\Slack\SlackMessenger;
use TomPHP\TimeTracker\Slack\TimeTracker;

final class LogCommandHandler implements CommandHandler
{
    /** @var TimeTracker */
    private $timeTracker;

    /** @var SlackMessenger */
    private $messenger;

    public function __construct(TimeTracker $timeTracker, SlackMessenger $messenger)
    {
        $this->timeTracker = $timeTracker;
        $this->messenger   = $messenger;
    }

    public function handle(string $slackHandle, Command $command)
    {
        $developer = $this->timeTracker->fetchDeveloperBySlackHandle($slackHandle);
        $project   = $this->timeTracker->fetchProjectByName($command->projectName());

        $this->timeTracker->logTimeEntry(
            $developer,
            $project,
            $command->date(),
            $command->period(),
            $command->description()
        );

        $this->messenger->send(sprintf(
            '%s logged %s against %s',
            $developer->name(),
            $command->period(),
            $project->name()
        ));
    }
}
