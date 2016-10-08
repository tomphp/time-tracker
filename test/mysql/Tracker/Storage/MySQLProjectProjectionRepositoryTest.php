<?php

namespace test\mysql\TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use test\unit\TomPHP\TimeTracker\Tracker\Storage\AbstractProjectProjectionsTest;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\Storage\MySQLProjectProjectionRepository;

final class MySQLProjectProjectionRepositoryTest extends AbstractProjectProjectionsTest
{
    /** @var ProjectProjections */
    private $projects;

    protected function setUp()
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s', getenv('MYSQL_HOSTNAME'), getenv('MYSQL_DBNAME'));
        $pdo = new PDO($dsn, getenv('MYSQL_USERNAME'), getenv('MYSQL_PASSWORD'));

        $pdo->exec('TRUNCATE `project_projections`');

        $this->projects = new MySQLProjectProjectionRepository($pdo);
    }

    protected function projects() : ProjectProjections
    {
        return $this->projects;
    }
}
