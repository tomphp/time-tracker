<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\CommandHandler;
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\SlackUserId;
use TomPHP\TimeTracker\Slack\TimeTracker;

final class LogCommandHandler implements CommandHandler
{
    /** @var TimeTracker */
    private $timeTracker;

    /** @var LinkedAccounts */
    private $linkedAccounts;

    public function __construct(TimeTracker $timeTracker, LinkedAccounts $linkedAccounts)
    {
        $this->timeTracker    = $timeTracker;
        $this->linkedAccounts = $linkedAccounts;
    }

    public function handle(SlackUserId $userId, Command $command) : array
    {
        $linkedAccount = $this->linkedAccounts->withSlackUserId($userId);
        $developer     = $this->timeTracker->fetchDeveloperById($linkedAccount->developerId());
        $project       = $this->timeTracker->fetchProjectByName($command->projectName());

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
