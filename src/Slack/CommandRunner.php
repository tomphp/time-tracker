<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

use Interop\Container\ContainerInterface;

final class CommandRunner
{
    /** string[] */
    private $commands;

    /** @var ContainerInterface */
    private $container;

    /** @var CommandSanitiser */
    private $sanitiser;

    /** @param Command[] $commands */
    public function __construct(
        ContainerInterface $container,
        CommandSanitiser $sanitiser,
        array $commands
    ) {
        $this->commands  = $commands;
        $this->container = $container;
        $this->sanitiser = $sanitiser;
    }

    public function run(SlackUserId $userId, string $commandString) : array
    {
        $commandString = $this->sanitiser->sanitise($commandString);

        list($name, $arguments) = explode(' ', $commandString, 2);

        if (!array_key_exists($name, $this->commands)) {
            return [
                'response_type' => 'ephemeral',
                'text'          => [
                    $name . ' is not a valid command',
                    'Valid commands are: ' . implode(', ', array_keys($this->commands)),
                ],
            ];
        }

        $command = $this->parser($name)->parse($arguments);

        return $this->handler($name)->handle($userId, $command);
    }

    private function parser(string $name) : CommandParser
    {
        return $this->container->get($this->serviceName($name, 'Parser'));
    }

    private function handler(string $name) : CommandHandler
    {
        return $this->container->get($this->serviceName($name, 'Handler'));
    }

    private function serviceName(string $commandName, string $type) : string
    {
        return $this->commands[$commandName] . $type;
    }
}
