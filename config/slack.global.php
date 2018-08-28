<?php declare(strict_types=1);
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Slack\CommandSanitiser;
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\TimeTracker;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\ProjectProjections;

return [
    'slack' => [
        'token'    => '', // @todo Regenerate and move to a secure location
        'commands' => [
            'log'  => Command\LogCommand::class,
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
                    CommandSanitiser::class,
                    'config.slack.commands',
                ],
            ],
            CommandSanitiser::class         => [],
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
