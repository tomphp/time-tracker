<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack\Storage;

use test\support\TestUsers\Fran;
use test\support\TestUsers\Mike;
use TomPHP\TimeTracker\Slack\LinkedAccount;
use TomPHP\TimeTracker\Slack\LinkedAccounts;

abstract class LinkedAccountsTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function linkedAccounts() : LinkedAccounts;

    /** @test */
    public function on_hasSlackUser_it_returns_false_if_no_LinkedAccount_added()
    {
        assertFalse($this->linkedAccounts()->hasSlackUser(Mike::slackUserId()));
    }

    /** @test */
    public function on_hasSlackUser_it_returns_true_if_a_LinkedAccount_with_that_user_id_has_been_added()
    {
        $this->linkedAccounts()->add(new LinkedAccount(Mike::id(), Mike::slackUserId()));

        assertTrue($this->linkedAccounts()->hasSlackUser(Mike::slackUserId()));
    }

    /** @test */
    public function on_hasDeveloper_it_returns_false_if_no_LinkedAccount_added()
    {
        assertFalse($this->linkedAccounts()->hasDeveloper(Mike::id()));
    }

    /** @test */
    public function on_hasDeveloper_it_returns_true_if_a_LinkedAccount_with_that_user_id_has_been_added()
    {
        $this->linkedAccounts()->add(new LinkedAccount(Mike::id(), Mike::slackUserId()));

        assertTrue($this->linkedAccounts()->hasDeveloper(Mike::id()));
    }

    /** @test */
    public function on_hasSlackUser_it_returns_false_if_a_LinkedAccount_with_a_different_user_id_has_been_added()
    {
        $this->linkedAccounts()->add(new LinkedAccount(Fran::id(), Fran::slackUserId()));

        assertFalse($this->linkedAccounts()->hasSlackUser(Mike::slackUserId()));
    }

    /** @test */
    public function on_withSlackUserId_it_returns_the_linked_account_with_that_id()
    {
        $account = new LinkedAccount(Fran::id(), Fran::slackUserId());

        $this->linkedAccounts()->add($account);

        assertEquals($account, $this->linkedAccounts()->withSlackUserId(Fran::slackUserId()));
    }

    /** @test */
    public function on_withSlackUserId_it_throws_if_no_matching_account_is_found()
    {
        $this->markTestIncomplete('Implement me');
    }
}
