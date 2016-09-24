<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

use Interop\Container\ContainerInterface;

final class CommandRunner
{
    /** string[] */
    private $commands;

    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container, array $commands)
    {
        $this->commands  = $commands;
        $this->container = $container;
    }

    /** @return void */
    public function run(string $slackHandle, string $commandString)
    {
        list($name, $arguments) = explode(' ', $commandString, 2);

        $command = $this->parser($name)->parse($arguments);

        $this->handler($name)->handle($slackHandle, $command);
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
