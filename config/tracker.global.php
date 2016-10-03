<?php

use TomPHP\TimeTracker\Tracker\EventHandlers\ProjectProjectionHandler;
use TomPHP\TimeTracker\Tracker\EventHandlers\TimeEntryProjectionHandler;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryProjectProjections;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryTimeEntryProjections;
use TomPHP\TimeTracker\Tracker\EventHandlers\DeveloperProjectionHandler;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;

return [
    'tracker' => [
        'event_handlers' => [
            DeveloperProjectionHandler::class,
            ProjectProjectionHandler::class,
            TimeEntryProjectionHandler::class,
        ],
    ],
    'di' => [
        'services' => [
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
