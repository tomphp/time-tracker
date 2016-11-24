<?php

use TomPHP\TimeTracker\Infrastructure\LoggerFactory;
use TomPHP\ContextLogger\ContextLoggerAware;

return [
    'logger' => [
        'name' => 'time-tracker',

        'papertrail' => [
            'host' => 'logs2.papertrailapp.com',
            'port' => 25926,
        ],
    ],

    'di' => [
        'services' => [
            'logger' => [
                'factory'   => LoggerFactory::class,
                'arguments' => [
                    'config.logger.name',
                    'config.logger.papertrail.host',
                    'config.logger.papertrail.port',
                    uniqid(),
                ],
            ],
        ],

        'inflectors' => [
            ContextLoggerAware::class => [
                'setLogger' => ['logger'],
            ],
        ],
    ],
];
