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
use test\unit\TomPHP\TimeTracker\Slack\Storage\LinkedAccountsTest;
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\Storage\MySQLLinkedAccountRepository;

final class MySQLLinkedAccountRepositoryTest extends LinkedAccountsTest
{
    use MySQLConnection;

    /** @var MySQLLinkedAccountRepository */
    private $linkedAccounts;

    public function setUp()
    {
        $this->clearTable('slack_linked_accounts');

        $this->linkedAccounts = new MySQLLinkedAccountRepository($this->pdo());
    }

    protected function linkedAccounts() : LinkedAccounts
    {
        return $this->linkedAccounts;
    }
}
