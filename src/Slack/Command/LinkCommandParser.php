<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\CommandParser;
use TomPHP\TimeTracker\Slack\Exception\CommandFormatInvalid;

final class LinkCommandParser implements CommandParser
{
    const REGEX = '/^to account (.*)$/';

    /** @return LinkCommand */
    public function parse(string $command) : Command
    {
        if (!preg_match(self::REGEX, $command, $matches)) {
            throw new CommandFormatInvalid(__CLASS__, $command);
        }

        return new LinkCommand(Email::fromString($matches[1]));
    }

    public function matchesFormat(string $command) : bool
    {
        return (bool) preg_match(self::REGEX, $command);
    }

    public function formatDescription() : string
    {
        return 'link to account [email address]';
    }
}
