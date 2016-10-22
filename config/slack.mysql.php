<?php declare(strict_types=1);
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\Storage\MySQLLinkedAccountRepository;

return [
    'di' => [
        'services' => [
            LinkedAccounts::class => [
                'class'     => MySQLLinkedAccountRepository::class,
                'arguments' => ['database'],
            ],
        ],
    ],
];
