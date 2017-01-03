<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack;

use Interop\Container\ContainerInterface;
use Prophecy\Argument;
use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\CommandHandler;
use TomPHP\TimeTracker\Slack\CommandParser;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Slack\CommandSanitiser;
use TomPHP\TimeTracker\Slack\SlackUserId;
use TomPHP\ContextLogger;
use TomPHP\ContextLogger\ContextLoggerAware;

final class CommandRunnerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContextLogger */
    private $logger;

    /** @var CommandRunner */
    private $subject;

    /** @var ContainerInterface */
    private $container;

    /** @var CommandParser */
    private $parser;

    /** @var CommandSanitiser */
    private $sanitiser;

    /** @var Command */
    private $command;

    /** @var SlackUserId */
    private $userId;

    protected function setUp()
    {
        $this->logger    = $this->prophesize(ContextLogger::class);
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->parser    = $this->prophesize(CommandParser::class);
        $this->command   = $this->prophesize(Command::class);
        $this->handler   = $this->prophesize(CommandHandler::class);
        $this->sanitiser = $this->prophesize(CommandSanitiser::class);

        $this->handler->handle(Argument::cetera())->willReturn([]);
        $this->container->get('Namespace\FooCommandParser')->willReturn($this->parser->reveal());
        $this->container->get('Namespace\BarCommandParser')->willReturn($this->parser->reveal());
        $this->container->get('Namespace\FooCommandHandler')->willReturn($this->handler->reveal());
        $this->container->get('Namespace\BarCommandHandler')->willReturn($this->handler->reveal());

        $this->parser->parse(Argument::any())->willReturn($this->command->reveal());
        $this->parser->matchesFormat(Argument::any())->willReturn(true);
        $this->parser->formatDescription()->willReturn('');

        $this->userId = SlackUserId::fromString('U1234567890');

        $this->subject = new CommandRunner(
            $this->container->reveal(),
            $this->sanitiser->reveal(),
            [
                'foo' => 'Namespace\FooCommand',
                'bar' => 'Namespace\BarCommand',
            ]
        );

        $this->subject->setLogger($this->logger->reveal());
    }

    /** @test */
    public function it_is_logger_aware()
    {
        assertInstanceOf(ContextLoggerAware::class, $this->subject);
    }

    /** @test */
    public function it_logs_the_command_at_debug_level()
    {
        $this->runCommand('foo command');

        $this->logger
            ->debug('Slack Command: foo command')
            ->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_fetches_the_parser_from_the_container()
    {
        $this->runCommand('foo command');

        $this->container->get('Namespace\FooCommandParser')->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_returns_an_error_if_the_command_is_unknown()
    {
        $result = $this->runCommand('unknown command');

        assertSame('ephemeral', $result['response_type']);
        assertSame('unknown is not a valid command', $result['text']);
        assertSame(
            [
                'text' => 'Valid commands are:',
                'foo',
                'bar',
            ],
            $result['attachments']
        );
    }

    /** @test */
    public function it_returns_an_error_if_the_command_format_is_invalid()
    {
        $this->parser->matchesFormat('invalid command')->willReturn(false);
        $this->parser->formatDescription()->willReturn('format description');

        $result = $this->runCommand('foo invalid command');

        assertSame('ephemeral', $result['response_type']);
        assertSame('Invalid foo command', $result['text']);
        assertSame(['text' => 'Format: format description'], $result['attachments']);
    }

    /** @test */
    public function it_logs_a_warning_if_the_command_format_is_invalid()
    {
        $this->parser->matchesFormat('invalid command')->willReturn(false);
        $this->parser->formatDescription()->willReturn('format description');

        $result = $this->runCommand('foo invalid command');

        $this->logger
            ->warning('Invalid command format for \'foo invalid command\'')
            ->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_parses_the_command()
    {
        $this->runCommand('bar command arguments sanitised');

        $this->parser->parse('command arguments sanitised')->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_fetches_the_command_handler()
    {
        $this->runCommand('foo command');

        $this->container->get('Namespace\FooCommandHandler')->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_handles_the_command()
    {
        $this->runCommand('foo command');

        $this->handler->handle($this->userId, $this->command)->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_returns_the_command_handler_response()
    {
        $result = ['the result'];
        $this->handler->handle(Argument::cetera())->willReturn($result);

        assertSame($result, $this->runCommand('foo command'));
    }

    private function runCommand(string $command) : array
    {
        $this->sanitiser->sanitise('unsanitised command')->willReturn($command);

        return $this->subject->run($this->userId, 'unsanitised command');
    }
}
