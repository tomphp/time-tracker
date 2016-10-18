<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

/** @final */
class CommandSanitiser
{
    public function sanitise(string $command) : string
    {
        return preg_replace('/\s+/', ' ', $command);
    }
}
