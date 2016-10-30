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
        $commandString          = $this->sanitiser->sanitise($commandString);
        list($name, $arguments) = explode(' ', $commandString, 2);
        if (!array_key_exists($name, $this->commands)) {
            return $this->unknownCommandResponse($name);
        }

        $parser = $this->parser($name);
        if (!$parser->matchesFormat($arguments)) {
            return $this->invalidFormatResponse($name, $parser);
        }
        $command = $parser->parse($arguments);

        return $this->handler($name)->handle($userId, $command);
    }

    private function unknownCommandResponse(string $name) : array
    {
        $attachments = array_merge(
            ['text' => 'Valid commands are:'],
            array_keys($this->commands)
        );

        return [
            'response_type' => 'ephemeral',
            'text'          => $name . ' is not a valid command',
            'attachments'   => $attachments,
        ];
    }

    private function invalidFormatResponse(string $name, CommandParser $parser) : array
    {
        return [
            'response_type' => 'ephemeral',
            'text'          => "Invalid $name command",
            'attachments'   => ['text' => 'Format: ' . $parser->formatDescription()],
        ];
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
