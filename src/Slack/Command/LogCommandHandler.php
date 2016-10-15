<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\CommandHandler;
use TomPHP\TimeTracker\Slack\TimeTracker;

final class LogCommandHandler implements CommandHandler
{
    /** @var TimeTracker */
    private $timeTracker;

    public function __construct(TimeTracker $timeTracker)
    {
        $this->timeTracker = $timeTracker;
    }

    public function handle(SlackHandle $slackHandle, Command $command) : array
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

        $message = sprintf(
            '%s logged %s against %s',
            $developer->name(),
            $command->period(),
            $project->name()
        );

        return [
            'response_type' => 'ephemeral',
            'text'          => $message,
        ];
    }
}
