<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack\Storage;

use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\Storage\MemoryLinkedAccounts;

final class MemoryLinkedAccountsTest extends LinkedAccountsTest
{
    /** MemoryLinkedAccounts */
    protected $linkedAccounts;

    protected function setUp()
    {
        $this->linkedAccounts = new MemoryLinkedAccounts();
    }

    protected function linkedAccounts() : LinkedAccounts
    {
        return $this->linkedAccounts;
    }
}
