<?php

namespace TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\CommandParser;

final class LinkCommandParser implements CommandParser
{
    /** @return LinkCommand */
    public function parse(string $command) : Command
    {
        preg_match('/^to account (.*)$/', $command, $matches);

        return new LinkCommand(Email::fromString($matches[1]));
    }
}
