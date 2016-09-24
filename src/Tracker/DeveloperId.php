<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

final class DeveloperId
{
    public static function generate() : self
    {
        return new self();
    }
}
