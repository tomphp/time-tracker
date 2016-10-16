<?php

namespace TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Common\SlackHandle;

interface LinkedAccounts
{
    /** @return void */
    public function add(LinkedAccount $account);

    public function hasSlackUser(SlackHandle $slackHandle) : bool;

    public function hasDeveloper(string $developerId) : bool;
}
