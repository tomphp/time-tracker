<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

final class Developer
{
    public static function create(DeveloperId $id, string $name) : self
    {
        return new self();
    }
}
