<?php declare(strict_types=1);

namespace test\mysql\TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use test\mysql\TomPHP\TimeTracker\MySQLConnection;
use test\unit\TomPHP\TimeTracker\Tracker\Storage\AbstractTimeEntryProjectionsTest;
use TomPHP\TimeTracker\Tracker\Storage\MySQLTimeEntryProjectionRepository;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

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
