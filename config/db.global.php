<?php

return [
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
        ],
    ],
];
