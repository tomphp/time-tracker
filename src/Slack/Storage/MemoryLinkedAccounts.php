<?php

namespace TomPHP\TimeTracker\Slack\Storage;

use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Slack\LinkedAccount;
use TomPHP\TimeTracker\Slack\LinkedAccounts;

final class MemoryLinkedAccounts implements LinkedAccounts
{
    /** @var bool[] */
    private $accountsBySlack = [];

    public function add(LinkedAccount $account)
    {
        $this->accountsBySlack[(string) $account->slackHandle()] = true;
    }

    public function hasSlackUser(SlackHandle $slackHandle) : bool
    {
        return isset($this->accountsBySlack[(string) $slackHandle]);
    }

    public function hasDeveloper(string $developerId) : bool
    {
    }
}
