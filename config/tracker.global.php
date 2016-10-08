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

return [
    'tracker' => [
        'event_handlers' => [
            DeveloperProjectionHandler::class,
            ProjectProjectionHandler::class,
            TimeEntryProjectionHandler::class,
        ],
    ],
    'db' => [
        'dsn'      => sprintf('mysql:host=%s;dbname=%s', getenv('MYSQL_HOSTNAME'), getenv('MYSQL_DBNAME')),
        'username' => getenv('MYSQL_USERNAME'),
        'password' => getenv('MYSQL_PASSWORD'),
    ],
    'di' => [
        'services' => [
            'database' => [
                'class' => PDO,
                'arguments' => [
                    'config.db.dsn',
                    'config.db.username',
                    'config.db.password',
                ],
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
