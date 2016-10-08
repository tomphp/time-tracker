<?php

use Phinx\Migration\AbstractMigration;

class CreateDeveloperProjectionsTableMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('developer_projections', ['id' => false, 'primary_key' => 'id']);

        $table->addColumn('id', 'string', ['limit' => '128'])
            ->addColumn('name', 'string', ['limit' => '2048'])
            ->addColumn('slackHandle', 'string', ['limit' => '128'])
            ->addIndex('slackHandle', ['unique' => true])
            ->create();
    }
}
