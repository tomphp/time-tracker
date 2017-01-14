<?php declare(strict_types=1);
use TomPHP\ContextLogger\ContextLoggerAware;
use TomPHP\TimeTracker\Infrastructure\LoggerFactory;

return [
    'logger' => [
        'name' => 'time-tracker',
    ],

    'di' => [
        'services' => [
            'logger' => [
                'factory'   => LoggerFactory::class,
                'arguments' => [
                    'config.logger.name',
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
