<?php

namespace test\mysql\TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Tracker\Storage\MySQLTimeEntryProjectionRepository;
use PDO;
use test\unit\TomPHP\TimeTracker\Tracker\Storage\AbstractTimeEntryProjectionsTest;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;
use test\mysql\TomPHP\TimeTracker\MySQLConnection;

final class MySQLTimeEntryProjectionRepositoryTest extends AbstractTimeEntryProjectionsTest
{
    use MySQLConnection;

    /** @var ProjectProjections */
    private $timeEntries;

    protected function setUp()
    {
        $this->clearTable('time_entry_projections');

        $this->timeEntries = new MySQLTimeEntryProjectionRepository($this->pdo());
    }

    protected function timeEntries() : TimeEntryProjections
    {
        return $this->timeEntries;
    }
}
