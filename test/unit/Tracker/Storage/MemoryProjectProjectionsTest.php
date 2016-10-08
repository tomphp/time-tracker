<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryProjectProjections;

final class MemoryProjectProjectionsTest extends AbstractProjectProjectionsTest
{
    /** @var ProjectProjections */
    private $projects;

    protected function setUp()
    {
        $this->projects = new MemoryProjectProjections();
    }

    protected function projects() : ProjectProjections
    {
        return $this->projects;
    }
}
