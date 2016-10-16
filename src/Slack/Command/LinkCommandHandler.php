<?php

namespace TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\CommandHandler;
use TomPHP\TimeTracker\Slack\LinkedAccount;
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\TimeTracker;

final class LinkCommandHandler implements CommandHandler
{
    const SUCCESS_MESSAGE              = 'Hi %s, your account has been successfully linked.';
    const SLACK_ALREADY_LINKED_MESSAGE = 'ERROR: Your account has already been linked.';

    /** @var TimeTracker */
    private $timeTracker;

    /** @var LinkedAccounts */
    private $linkedAccounts;

    public function __construct(TimeTracker $timeTracker, LinkedAccounts $linkedAccounts)
    {
        $this->timeTracker    = $timeTracker;
        $this->linkedAccounts = $linkedAccounts;
    }

    public function handle(SlackHandle $slackHandle, Command $command) : array
    {
        if ($this->linkedAccounts->hasSlackUser($slackHandle)) {
            return [
                'response_type' => 'ephemeral',
                'text'          => self::SLACK_ALREADY_LINKED_MESSAGE,
            ];
        }

        $developer = $this->timeTracker->fetchDeveloperByEmail($command->email());

        $this->linkedAccounts->add(new LinkedAccount($developer->id(), $slackHandle));

        return [
            'response_type' => 'ephemeral',
            'text'          => sprintf(self::SUCCESS_MESSAGE, $developer->name()),
        ];
    }
}
