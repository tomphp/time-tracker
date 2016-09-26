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
            ProjectProjectionHandler::class => [
                'arguments' => [ProjectProjections::class],
            ],
            TimeEntryProjectionHandler::class => [
                'arguments' => [TimeEntryProjections::class],
            ],
        ],
    ],
];
