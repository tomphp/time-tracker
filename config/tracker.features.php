<?php

use TomPHP\TimeTracker\Tracker\EventHandlers\ProjectProjectionHandler;
use TomPHP\TimeTracker\Tracker\EventHandlers\TimeEntryProjectionHandler;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryProjectProjections;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryTimeEntryProjections;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryDeveloperProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryEventStore;

return [
    'di' => [
        'services' => [
            DeveloperProjections::class => [
                'class' => MemoryDeveloperProjections::class,
            ],
            ProjectProjections::class => [
                'class' => MemoryProjectProjections::class,
            ],
            TimeEntryProjections::class => [
                'class' => MemoryTimeEntryProjections::class,
            ],
            MemoryEventStore::class => [
                'class' => MySQLEventStore::class,
                'arguments' => ['database'],
            ],
        ],
    ],
];
