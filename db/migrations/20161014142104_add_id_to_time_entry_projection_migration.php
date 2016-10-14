<?php

use Phinx\Migration\AbstractMigration;

class AddIdToTimeEntryProjectionMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('time_entry_projections');

        $table->changeColumn('id', 'string', array('limit' => 128))
              ->save();
    }
}
