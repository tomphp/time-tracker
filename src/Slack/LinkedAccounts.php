<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Common\DeveloperId;

interface LinkedAccounts
{
    /** @return void */
    public function add(LinkedAccount $account);

    public function hasSlackUser(SlackUserId $userId) : bool;

    public function hasDeveloper(DeveloperId $developerId) : bool;

    public function withSlackUserId(SlackUserId $userId) : LinkedAccount;
}
