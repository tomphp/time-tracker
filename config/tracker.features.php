<?php

use TomPHP\TimeTracker\Tracker\EventHandlers\ProjectProjectionHandler;
use TomPHP\TimeTracker\Tracker\EventHandlers\TimeEntryProjectionHandler;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryProjectProjections;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryTimeEntryProjections;

return [
    'di' => [
        'services' => [
            ProjectProjections::class => [
                'class' => MemoryProjectProjections::class,
            ],
            TimeEntryProjections::class => [
                'class' => MemoryTimeEntryProjections::class,
            ],
        ],
    ],
];
