<?php declare(strict_types=1);
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\EventHandlers\DeveloperProjectionHandler;
use TomPHP\TimeTracker\Tracker\EventHandlers\EventStoreHandler;
use TomPHP\TimeTracker\Tracker\EventHandlers\ProjectProjectionHandler;
use TomPHP\TimeTracker\Tracker\EventHandlers\TimeEntryProjectionHandler;
use TomPHP\TimeTracker\Tracker\EventStore;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

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
        ],
    ],
];
