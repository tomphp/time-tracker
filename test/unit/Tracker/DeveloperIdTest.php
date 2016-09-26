<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Tracker\DeveloperId;

final class DeveloperIdTest extends AbstractEntityIdTest
{
    protected function className() : string
    {
        return DeveloperId::class;
    }
}
