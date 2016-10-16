<?php

namespace TomPHP\TimeTracker\Tracker;

use Ramsey\Uuid\Uuid;

trait IdGenerator
{
    /** @return static */
    public static function generate()
    {
        return static::fromString((string) Uuid::uuid4());
    }
}
