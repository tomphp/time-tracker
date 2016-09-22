<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

final class UserId
{
    public static function generate() : self
    {
        return new self();
    }
}
