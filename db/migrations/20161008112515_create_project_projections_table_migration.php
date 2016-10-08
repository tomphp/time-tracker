<?php

use Phinx\Migration\AbstractMigration;

class CreateProjectProjectionsTableMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('project_projections', ['id' => false, 'primary_key' => 'id']);

        $table->addColumn('id', 'string', ['limit' => 128])
            ->addColumn('name', 'string', ['limit' => 128])
            ->addColumn('totalTime', 'string')
            ->addIndex('name', ['unique' => true])
            ->create();
    }
}
