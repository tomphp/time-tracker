<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\CommandHandler;
use TomPHP\TimeTracker\Slack\LinkedAccount;
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\SlackUserId;
use TomPHP\TimeTracker\Slack\TimeTracker;

final class LinkCommandHandler implements CommandHandler
{
    private const SUCCESS_MESSAGE                  = 'Hi %s, your account has been successfully linked.';
    private const SLACK_ALREADY_LINKED_MESSAGE     = 'ERROR: Your Slack user has already been linked.';
    private const DEVELOPER_ALREADY_LINKED_MESSAGE = 'ERROR: This developer account has already been linked.';

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
        if ($this->linkedAccounts->hasSlackUser($userId)) {
            return [
                'response_type' => 'ephemeral',
                'text'          => self::SLACK_ALREADY_LINKED_MESSAGE,
            ];
        }

        $developer = $this->timeTracker->fetchDeveloperByEmail($command->email());

        if ($this->linkedAccounts->hasDeveloper($developer->id())) {
            return [
                'response_type' => 'ephemeral',
                'text'          => self::DEVELOPER_ALREADY_LINKED_MESSAGE,
            ];
        }

        $this->linkedAccounts->add(new LinkedAccount($developer->id(), $userId));

        return [
            'response_type' => 'ephemeral',
            'text'          => sprintf(self::SUCCESS_MESSAGE, $developer->name()),
        ];
    }
}
