<?php declare(strict_types=1);

namespace test\mysql\TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use test\mysql\TomPHP\TimeTracker\MySQLConnection;
use test\unit\TomPHP\TimeTracker\Tracker\Storage\AbstractDeveloperProjectionsTest;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\Storage\MySQLDeveloperProjectionRepository;

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
