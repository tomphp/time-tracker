<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack\Exception;

final class CommandFormatInvalid extends \LogicException
{
    public function __construct(string $parser, string $command)
    {
        parent::__construct(sprintf(
            'Invalid command "%s" for %s.',
            $command,
            $parser
        ));
    }
}
