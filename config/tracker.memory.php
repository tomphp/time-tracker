<?php declare(strict_types=1);
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\EventStore;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryDeveloperProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryEventStore;
use TomPHP\TimeTracker\Tracker\Storage\MemoryProjectProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryTimeEntryProjections;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

return [
    'di' => [
        'services' => [
            EventStore::class => [
                'class'     => MemoryEventStore::class,
            ],
            DeveloperProjections::class => [
                'class' => MemoryDeveloperProjections::class,
            ],
            ProjectProjections::class => [
                'class' => MemoryProjectProjections::class,
            ],
            TimeEntryProjections::class => [
                'class' => MemoryTimeEntryProjections::class,
            ],
        ],
    ],
];
