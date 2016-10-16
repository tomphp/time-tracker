<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

interface CommandHandler
{
    public function handle(SlackUserId $userId, Command $command) : array;
}
