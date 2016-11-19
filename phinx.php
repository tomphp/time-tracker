<?php

use TomPHP\TimeTracker\Bootstrap;
use Slim\Container;

$container = new Container();

Bootstrap::run($container);

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds'      => '%%PHINX_CONFIG_DIR%%/db/seeds',
    ],
    'environments' => [
        'default_database' => 'db',
        'db' => [
            'name'       => $container->get('config.db.name'),
            'connection' => $container->get('database'),
        ],
    ],
];
