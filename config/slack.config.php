<?php

use TomPHP\TimeTracker\Slack\TimeTracker;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Slack\Command\LogCommand;
use TomPHP\TimeTracker\Slack\Command\LogCommandParser;
use TomPHP\TimeTracker\Slack\Command\LogCommandHandler;
use TomPHP\TimeTracker\Slack\Date;
use TomPHP\TimeTracker\Slack\SlackMessenger;

return [
    'slack' => [
        'commands' => [
            'log' => LogCommand::class,
        ],
        'today' => Date::today(),
    ],
    'di' => [
        'services' => [
            TimeTracker::class => [],
            SlackMessenger::class => [],
            CommandRunner::class => [
                'arguments' => [
                    'CONTAINER', // requires an update to container-configurator
                    'config.slack.commands',
                ],
            ],
            SlackMessenger::class => [],
            LogCommandParser::class => [
                'arguments' => ['config.slack.today'],
            ],
            LogCommandHandler::class => [
                'arguments' => [TimeTracker::class, SlackMessenger::class],
            ],
        ],
    ],
];
