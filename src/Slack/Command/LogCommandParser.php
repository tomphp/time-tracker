<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\CommandParser;
use TomPHP\TimeTracker\Slack\Date;
use TomPHP\TimeTracker\Slack\Period;

final class LogCommandParser implements CommandParser
{
    /** @var Date */
    private $today;

    public function __construct(Date $today)
    {
        $this->today = $today;
    }

    public function parse(string $command) : Command
    {
        preg_match('/(\d+)hrs against (.*) for (.*)/', $command, $matches);

        return new LogCommand(
            $matches[2],
            $this->today,
            Period::fromString($matches[1]),
            $matches[3]
        );
    }
}
