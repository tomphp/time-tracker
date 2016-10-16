<?php

namespace test\unit\TomPHP\TimeTracker\Slack\Storage;

use test\support\TestUsers\Fran;
use test\support\TestUsers\Mike;
use TomPHP\TimeTracker\Slack\LinkedAccount;
use TomPHP\TimeTracker\Slack\Storage\MemoryLinkedAccounts;

final class MemoryLinkedAccountsTest extends \PHPUnit_Framework_TestCase
{
    /** @var MemoryLinkedAcounts */
    private $accounts;

    protected function setUp()
    {
        $this->accounts = new MemoryLinkedAccounts();
    }

    /** @test */
    public function on_hasSlackUser_it_returns_false_if_no_LinkedAccount_added()
    {
        assertFalse($this->accounts->hasSlackUser(Mike::slackUserId()));
    }

    /** @test */
    public function on_hasSlackUser_it_returns_true_if_a_LinkedAccount_with_that_user_id_has_been_added()
    {
        $this->accounts->add(new LinkedAccount(Mike::id(), Mike::slackUserId()));

        assertTrue($this->accounts->hasSlackUser(Mike::slackUserId()));
    }

    /** @test */
    public function on_hasSlackUser_it_returns_false_if_a_LinkedAccount_with_a_different_user_id_has_been_added()
    {
        $this->accounts->add(new LinkedAccount(Fran::id(), Fran::slackUserId()));

        assertFalse($this->accounts->hasSlackUser(Mike::slackUserId()));
    }
}
