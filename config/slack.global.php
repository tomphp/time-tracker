<?php

use TomPHP\TimeTracker\Slack\TimeTracker;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryDeveloperProjections;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\ContainerConfigurator\Configurator;

return [
    'slack' => [
        'commands' => [
            'log' => Command\LogCommand::class,
            'link' => Command\LinkCommand::class,
        ],
        'today' => Date::today(),
    ],
    'di' => [
        'services' => [
            DeveloperProjections::class => [
                'class' => MemoryDeveloperProjections::class,
            ],
            TimeTracker::class => [
                'arguments' => [
                    DeveloperProjections::class,
                    ProjectProjections::class,
                ],
            ],
            CommandRunner::class => [
                'arguments' => [
                    Configurator::container(),
                    'config.slack.commands',
                ],
            ],
            Command\LogCommandParser::class => [
                'arguments' => ['config.slack.today'],
            ],
            Command\LogCommandHandler::class => [
                'arguments' => [TimeTracker::class],
            ],
            Command\LinkCommandParser::class => [
            ],
            Command\LinkCommandHandler::class => [
                'arguments' => [TimeTracker::class],
            ],
        ],
    ],
];
