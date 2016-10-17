<?php

use TomPHP\TimeTracker\Slack\TimeTracker;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryDeveloperProjections;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\Storage\MemoryLinkedAccounts;

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
            LinkedAccounts::class => [
                'class' => MemoryLinkedAccounts::class,
            ],
            Command\LogCommandParser::class => [
                'arguments' => ['config.slack.today'],
            ],
            Command\LogCommandHandler::class => [
                'arguments' => [TimeTracker::class, LinkedAccounts::class],
            ],
            Command\LinkCommandParser::class => [
            ],
            Command\LinkCommandHandler::class => [
                'arguments' => [TimeTracker::class, LinkedAccounts::class],
            ],
        ],
    ],
];
