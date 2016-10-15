<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Common\SlackHandle;

interface CommandHandler
{
    public function handle(SlackHandle $slackHandle, Command $command) : array;
}
