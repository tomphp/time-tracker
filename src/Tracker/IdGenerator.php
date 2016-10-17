<?php declare(strict_types=1);

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
