<?php

use Phinx\Migration\AbstractMigration;

class AddSlackLinkedAccountsTableMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('slack_linked_accounts');

        $table->addColumn('slackUserId', 'string', ['limit' => 128])
            ->addColumn('developerId', 'string', ['limit' => 128])
            ->addIndex(['slackUserId', 'developerId'], ['unique' => true])
            ->create();
    }
}
