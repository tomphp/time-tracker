<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

final class User
{
    public static function create(UserId $id, string $name) : self
    {
        return new self();
    }
}
