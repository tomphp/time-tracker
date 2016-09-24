<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

interface CommandParser
{
    public function parse(string $command) : Command;
}
