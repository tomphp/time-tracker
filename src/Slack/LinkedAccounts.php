<?php

namespace TomPHP\TimeTracker\Slack;

interface LinkedAccounts
{
    /** @return void */
    public function add(LinkedAccount $account);

    public function hasSlackUser(SlackUserId $userId) : bool;

    public function hasDeveloper(string $developerId) : bool;

    public function withSlackUserId(SlackUserId $userId) : LinkedAccount;
}
