<?php

namespace test\mysql\TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use TomPHP\TimeTracker\Tracker\Storage\MySQLDeveloperProjectionRepository;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use test\unit\TomPHP\TimeTracker\Tracker\Storage\AbstractDeveloperProjectionsTest;
use test\mysql\TomPHP\TimeTracker\MySQLConnection;

final class MySQLDeveloperProjectionRepositoryTest extends AbstractDeveloperProjectionsTest
{
    use MySQLConnection;

    /** @var MySQLDeveloperProjectionRepository */
    private $developers;

    public function setUp()
    {
        $this->clearTable('developer_projections');

        $this->developers = new MySQLDeveloperProjectionRepository($this->pdo());
    }

    protected function developers() : DeveloperProjections
    {
        return $this->developers;
    }
}
