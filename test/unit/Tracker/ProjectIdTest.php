<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Tracker\ProjectId;

final class ProjectIdTest extends AbstractEntityIdTest
{
    protected function className() : string
    {
        return ProjectId::class;
    }
}