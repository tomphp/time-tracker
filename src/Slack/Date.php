<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

final class Date
{
    public static function today() : self
    {
        return new self();
    }

    public static function fromString(string $string) : self
    {
        return new self();
    }
}
