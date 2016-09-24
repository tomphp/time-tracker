<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

interface CommandHandler
{
    /** @return void */
    public function handle(string $slackHandle, Command $command);
}
