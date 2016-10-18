<?php declare(strict_types=1);

namespace test\mysql\TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use test\mysql\TomPHP\TimeTracker\MySQLConnection;
use test\unit\TomPHP\TimeTracker\Tracker\Storage\AbstractProjectProjectionsTest;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\Storage\MySQLProjectProjectionRepository;

final class MySQLProjectProjectionRepositoryTest extends AbstractProjectProjectionsTest
{
    use MySQLConnection;

    /** @var ProjectProjections */
    private $projects;

    protected function setUp()
    {
        $this->clearTable('project_projections');

        $this->projects = new MySQLProjectProjectionRepository($this->pdo());
    }

    protected function projects() : ProjectProjections
    {
        return $this->projects;
    }
}
