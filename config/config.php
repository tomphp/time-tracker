<?php

use TomPHP\TimeTracker\Domain\EventHandlers\ProjectProjectionHandler;
use TomPHP\TimeTracker\Domain\EventHandlers\TimeEntryProjectionHandler;
use TomPHP\TimeTracker\Domain\ProjectProjections;
use TomPHP\TimeTracker\Storage\MemoryProjectProjections;
use TomPHP\TimeTracker\Domain\TimeEntryProjections;
use TomPHP\TimeTracker\Storage\MemoryTimeEntryProjections;

return [
    'event_handlers' => [
        ProjectProjectionHandler::class,
        TimeEntryProjectionHandler::class,
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
