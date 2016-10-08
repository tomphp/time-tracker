<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Tracker\Storage\MemoryTimeEntryProjections;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

final class MemoryTimeEntryProjectionsTest extends AbstractTimeEntryProjectionsTest
{
    /** @var TimeEntryProjections */
    private $timeEntries;

    protected function setUp()
    {
        $this->timeEntries = new MemoryTimeEntryProjections();
    }

    protected function timeEntries() : TimeEntryProjections
    {
        return $this->timeEntries;
    }
}
