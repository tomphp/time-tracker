<?php

use TomPHP\TimeTracker\Tracker\EventHandlers\ProjectProjectionHandler;
use TomPHP\TimeTracker\Tracker\EventHandlers\TimeEntryProjectionHandler;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryProjectProjections;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryTimeEntryProjections;

return [
    'tracker' => [
        'event_handlers' => [
            ProjectProjectionHandler::class,
            TimeEntryProjectionHandler::class,
        ],
    ],
    'di' => [
        'services' => [
            ProjectProjections::class => [
                'class' => MemoryProjectProjections::class,
            ],
            ProjectProjectionHandler::class => [
                'arguments' => [ProjectProjections::class],
            ],
            TimeEntryProjections::class => [
                'class' => MemoryTimeEntryProjections::class,
            ],
            TimeEntryProjectionHandler::class => [
                'arguments' => [TimeEntryProjections::class],
            ],
        ],
    ],
];
