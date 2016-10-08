<?php

namespace test\mysql\TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use TomPHP\TimeTracker\Tracker\Storage\MySQLDeveloperProjectionRepository;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use test\unit\TomPHP\TimeTracker\Tracker\Storage\AbstractDeveloperProjectionsTest;

final class MySQLDeveloperProjectionRepositoryTest extends AbstractDeveloperProjectionsTest
{
    /** @var MySQLDeveloperProjectionRepository */
    private $developers;

    public function setUp()
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s', getenv('MYSQL_HOSTNAME'), getenv('MYSQL_DBNAME'));
        $pdo = new PDO($dsn, getenv('MYSQL_USERNAME'), getenv('MYSQL_PASSWORD'));

        $pdo->exec('TRUNCATE `developer_projections`');

        $this->developers = new MySQLDeveloperProjectionRepository($pdo);
    }

    protected function developers() : DeveloperProjections
    {
        return $this->developers;
    }
}
