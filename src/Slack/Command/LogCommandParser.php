<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\CommandParser;

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
        $regex = sprintf(
            '/^(?<period>%s) against (?<project>.*) for (?<description>.*)$/',
            Period::REGEX
        );

        preg_match($regex, $command, $matches);

        return new LogCommand(
            $matches['project'],
            $this->today,
            Period::fromString($matches['period']),
            $matches['description']
        );
    }
}
