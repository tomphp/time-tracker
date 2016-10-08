<?php

namespace test\mysql\TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use TomPHP\TimeTracker\Tracker\Storage\MySQLDeveloperProjectionRepository;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Common\SlackHandle;

final class MySQLDeveloperProjectionRepositoryTest extends \PHPUnit_Framework_TestCase
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

    /** @test */
    public function on_withSlackHandle_it_returns_the_developer_with_that_handle()
    {
        $developerId = DeveloperId::generate();
        $developer   = new DeveloperProjection($developerId, 'Tom', SlackHandle::fromString('tom'));

        $this->developers->add($developer);

        assertEquals($developer, $this->developers->withSlackHandle(SlackHandle::fromString('tom')));
    }

    /** @test */
    public function on_withSlackHandle_it_throws_if_there_is_no_developer_projection_with_the_given_handle()
    {
        $this->markTestIncomplete();
    }
}
