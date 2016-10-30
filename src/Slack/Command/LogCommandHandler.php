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
        if (!$this->linkedAccounts->hasSlackUser($userId)) {
            return $this->formatMessage(
                'You Slack user has not been linked to an account',
                [],
                'Please use the link command to connect your user'
            );
        }

        if (!$this->timeTracker->hasProjectWithName($command->projectName())) {
             return $this->formatMessage('Project %s was not found.', [$command->projectName()]);
        }

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

        return $this->formatMessage(
            '%s logged %s against %s',
            [$developer->name(), $command->period(), $project->name()]
        );
    }

    private function formatMessage(string $message, array $params = [], string $extended = null) : array
    {
        $result = [
            'response_type' => 'ephemeral',
            'text'          => sprintf($message, ...$params),
        ];

        if ($extended) {
            $result['attachments'] = ['text' => $extended];
        }

        return $result;
    }
}
