<?php

namespace TomPHP\TimeTracker\Slack\Storage;

use TomPHP\TimeTracker\Slack\LinkedAccount;
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\SlackUserId;

final class MemoryLinkedAccounts implements LinkedAccounts
{
    /** @var bool[] */
    private $accountsBySlack = [];

    public function add(LinkedAccount $account)
    {
        $this->accountsBySlack[(string) $account->slackUserId()] = true;
    }

    public function hasSlackUser(SlackUserId $userId) : bool
    {
        return isset($this->accountsBySlack[(string) $userId]);
    }

    public function hasDeveloper(string $developerId) : bool
    {
    }

    public function withSlackUserId(SlackUserId $userId) : LinkedAccount
    {
    }
}
