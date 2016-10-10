<?php

use TomPHP\TimeTracker\Slack\TimeTracker;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Slack\Command\LogCommand;
use TomPHP\TimeTracker\Slack\Command\LogCommandParser;
use TomPHP\TimeTracker\Slack\Command\LogCommandHandler;
use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Slack\SlackMessenger;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryDeveloperProjections;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\ContainerConfigurator\Configurator;

return [
    'slack' => [
        'commands' => [
            'log' => LogCommand::class,
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
            SlackMessenger::class => [],
            CommandRunner::class => [
                'arguments' => [
                    Configurator::container(),
                    'config.slack.commands',
                ],
            ],
            LogCommandParser::class => [
                'arguments' => ['config.slack.today'],
            ],
            LogCommandHandler::class => [
                'arguments' => [TimeTracker::class, SlackMessenger::class],
            ],
        ],
    ],
];
