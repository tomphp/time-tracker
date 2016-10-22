<?php declare(strict_types=1);
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\Storage\MemoryLinkedAccounts;

return [
    'di' => [
        'services' => [
            LinkedAccounts::class => [
                'class' => MemoryLinkedAccounts::class,
            ],
        ],
    ],
];
