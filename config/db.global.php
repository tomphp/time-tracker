<?php declare(strict_types=1);

if (getenv('VCAP_SERVICES')) {
    $vcapServices = json_decode(getenv('VCAP_SERVICES'), true);
    $databases    = $vcapServices['cleardb'];
    $database     = $databases[0]['credentials'];
} else {
    $database = [
        'hostname' => getenv('MYSQL_HOSTNAME'),
        'username' => getenv('MYSQL_USERNAME'),
        'password' => getenv('MYSQL_PASSWORD'),
        'name'     => getenv('MYSQL_DBNAME'),
        'port'     => getenv('MYSQL_PORT') ?: 3306,
    ];
}

return [
    'db' => [
        'dsn'      => sprintf(
            'mysql:host=%s;dbname=%s;port=%d',
            $database['hostname'],
            $database['name'],
            $database['port']
        ),
        'name'     => $database['name'],
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
