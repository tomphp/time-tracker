<?php

use TomPHP\TimeTracker\Tracker\EventHandlers\ProjectProjectionHandler;
use TomPHP\TimeTracker\Tracker\EventHandlers\TimeEntryProjectionHandler;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryProjectProjections;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryTimeEntryProjections;
use TomPHP\TimeTracker\Tracker\EventHandlers\DeveloperProjectionHandler;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\Storage\MySQLDeveloperProjectionRepository;
use TomPHP\TimeTracker\Tracker\Storage\MySQLProjectProjectionRepository;
use TomPHP\TimeTracker\Tracker\Storage\MySQLTimeEntryProjectionRepository;
use TomPHP\TimeTracker\Tracker\EventHandlers\EventStoreHandler;
use TomPHP\TimeTracker\Tracker\EventStore;
use TomPHP\TimeTracker\Tracker\Storage\MySQLEventStore;

return [
    'tracker' => [
        'event_handlers' => [
            EventStoreHandler::class,
            DeveloperProjectionHandler::class,
            ProjectProjectionHandler::class,
            TimeEntryProjectionHandler::class,
        ],
    ],
    'di' => [
        'services' => [
            EventStoreHandler::class => [
                'arguments' => [EventStore::class],
            ],
            DeveloperProjectionHandler::class => [
                'arguments' => [DeveloperProjections::class],
            ],
            ProjectProjectionHandler::class => [
                'arguments' => [ProjectProjections::class],
            ],
            TimeEntryProjectionHandler::class => [
                'arguments' => [TimeEntryProjections::class],
            ],
            EventStore::class => [
                'class' => MySQLEventStore::class,
                'arguments' => ['database'],
            ],
            DeveloperProjections::class => [
                'class' => MySQLDeveloperProjectionRepository::class,
                'arguments' => ['database'],
            ],
            ProjectProjections::class => [
                'class' => MySQLProjectProjectionRepository::class,
                'arguments' => ['database'],
            ],
            TimeEntryProjections::class => [
                'class' => MySQLTimeEntryProjectionRepository::class,
                'arguments' => ['database'],
            ],
        ],
    ],
];
