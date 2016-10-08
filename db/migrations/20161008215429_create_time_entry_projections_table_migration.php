<?php

use Phinx\Migration\AbstractMigration;

class CreateTimeEntryProjectionsTableMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('time_entry_projections');

        $table->addColumn('projectId', 'string', ['limit' => 128])
            ->addColumn('developerId', 'string', ['limit' => 128])
            ->addColumn('date', 'string')
            ->addColumn('period', 'string')
            ->addColumn('description', 'string')
            ->create();
    }
}
