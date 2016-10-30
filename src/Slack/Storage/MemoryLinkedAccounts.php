<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack\Storage;

use TomPHP\TimeTracker\Common\DeveloperId;
use TomPHP\TimeTracker\Slack\LinkedAccount;
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\SlackUserId;

final class MemoryLinkedAccounts implements LinkedAccounts
{
    /** @var LinkedAccount[] */
    private $accountsBySlack = [];

    /** @var LinkedAccount[] */
    private $accountsByDeveloper = [];

    public function add(LinkedAccount $account)
    {
        $this->accountsBySlack[(string) $account->slackUserId()]     = $account;
        $this->accountsByDeveloper[(string) $account->developerId()] = $account;
    }

    public function hasSlackUser(SlackUserId $userId) : bool
    {
        return isset($this->accountsBySlack[(string) $userId]);
    }

    public function hasDeveloper(DeveloperId $developerId) : bool
    {
        return isset($this->accountsByDeveloper[(string) $developerId]);
    }

    public function withSlackUserId(SlackUserId $userId) : LinkedAccount
    {
        return $this->accountsBySlack[(string) $userId];
    }
}
