<?php declare(strict_types=1);

namespace test\mysql\TomPHP\TimeTracker\Tracker\Storage;

use PDO;
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
