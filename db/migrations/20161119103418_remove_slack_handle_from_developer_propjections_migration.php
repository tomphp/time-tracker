<?php

use Phinx\Migration\AbstractMigration;

class RemoveSlackHandleFromDeveloperPropjectionsMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('developer_projections', ['id' => false, 'primary_key' => 'id']);

        $table->removeColumn('slackHandle')
            ->addIndex('email')
            ->update();
    }
}
