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
        preg_match($this->regex(), $command, $matches);

        if ($matches['when'] === 'yesterday') {
            $date = Date::yesterday();
        } else {
            $date = $this->today; // <-- don't inject
        }

        return new LogCommand(
            $matches['project'],
            $date,
            Period::fromString($matches['period']),
            $matches['description']
        );
    }

    public function matchesFormat(string $command) : bool
    {
        return (bool) preg_match($this->regex(), $command);
    }

    public function formatDescription() : string
    {
        return 'log [time] against [project] for [description]';
    }

    private function regex() : string
    {
        return sprintf(
            '/^(?<period>%s) (?:(?<when>yesterday) )?against (?<project>.*) for (?<description>.*)$/',
            Period::REGEX
        );
    }
}
