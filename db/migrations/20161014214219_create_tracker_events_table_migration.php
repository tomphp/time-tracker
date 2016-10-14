<?php

use Phinx\Migration\AbstractMigration;

class CreateTrackerEventsTableMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('tracker_events');

        $table
            ->addColumn('name', 'string', ['limit' => 256])
            ->addColumn('aggregateId', 'string', ['limit' => '128'])
            ->addColumn('aggregateName', 'string', ['limit' => '256'])
            ->addColumn('created', 'timestamp')
            ->addColumn('data', 'text')
            ->addIndex('aggregateId')
            ->create();
    }
}
