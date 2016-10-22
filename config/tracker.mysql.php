<?php declare(strict_types=1);
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\EventStore;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\Storage\MySQLDeveloperProjectionRepository;
use TomPHP\TimeTracker\Tracker\Storage\MySQLEventStore;
use TomPHP\TimeTracker\Tracker\Storage\MySQLProjectProjectionRepository;
use TomPHP\TimeTracker\Tracker\Storage\MySQLTimeEntryProjectionRepository;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

return [
    'di' => [
        'services' => [
            EventStore::class => [
                'class'     => MySQLEventStore::class,
                'arguments' => ['database'],
            ],
            DeveloperProjections::class => [
                'class'     => MySQLDeveloperProjectionRepository::class,
                'arguments' => ['database'],
            ],
            ProjectProjections::class => [
                'class'     => MySQLProjectProjectionRepository::class,
                'arguments' => ['database'],
            ],
            TimeEntryProjections::class => [
                'class'     => MySQLTimeEntryProjectionRepository::class,
                'arguments' => ['database'],
            ],
        ],
    ],
];
