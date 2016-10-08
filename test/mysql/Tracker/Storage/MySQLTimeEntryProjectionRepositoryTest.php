<?php

namespace test\mysql\TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Tracker\Storage\MySQLTimeEntryProjectionRepository;
use PDO;
use test\unit\TomPHP\TimeTracker\Tracker\Storage\AbstractTimeEntryProjectionsTest;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

final class MySQLTimeEntryProjectionRepositoryTest extends AbstractTimeEntryProjectionsTest
{
    /** @var ProjectProjections */
    private $timeEntries;

    protected function setUp()
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s', getenv('MYSQL_HOSTNAME'), getenv('MYSQL_DBNAME'));
        $pdo = new PDO($dsn, getenv('MYSQL_USERNAME'), getenv('MYSQL_PASSWORD'));

        $pdo->exec('TRUNCATE `time_entry_projections`');

        $this->timeEntries = new MySQLTimeEntryProjectionRepository($pdo);
    }

    protected function timeEntries() : TimeEntryProjections
    {
        return $this->timeEntries;
    }
}
