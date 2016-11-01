<?php declare(strict_types=1);

$vcapServices = json_decode(getenv('VCAP_SERVICES'), true);
$databases    = $vcapServices['cleardb'];
$database     = $databases[0]['credentials'];

return [
    'db' => [
        'dsn'      => sprintf(
            'mysql:host=%s;dbname=%s;port=%d',
            $database['hostname'],
            $database['name'],
            $database['port']
        ),
        'username' => $database['username'],
        'password' => $database['password'],
    ],
    'di' => [
        'services' => [
            'database' => [
                'class'     => \PDO::class,
                'arguments' => [
                    'config.db.dsn',
                    'config.db.username',
                    'config.db.password',
                ],
            ],
        ],
    ],
];
