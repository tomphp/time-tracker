<?php

use Phinx\Migration\AbstractMigration;

class CreateEmailColumnInDeveloperProjectTableMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('developer_projections');

        $table->addColumn('email', 'string', ['limit' => '1024'])
            ->save();
    }
}
