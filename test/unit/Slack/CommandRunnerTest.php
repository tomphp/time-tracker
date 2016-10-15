<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack;

use Interop\Container\ContainerInterface;
use Prophecy\Argument;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\CommandHandler;
use TomPHP\TimeTracker\Slack\CommandParser;
use TomPHP\TimeTracker\Slack\CommandRunner;

final class CommandRunnerTest extends \PHPUnit_Framework_TestCase
{
    /** @var CommandRunner */
    private $subject;

    /** @var ContainerInterface */
    private $container;

    /** @var CommandParser */
    private $parser;

    /** @var Command */
    private $command;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->parser    = $this->prophesize(CommandParser::class);
        $this->command   = $this->prophesize(Command::class);
        $this->handler   = $this->prophesize(CommandHandler::class);

        $this->handler->handle(Argument::cetera())->willReturn([]);
        $this->container->get('Namespace\FooCommandParser')->willReturn($this->parser->reveal());
        $this->container->get('Namespace\BarCommandParser')->willReturn($this->parser->reveal());
        $this->container->get('Namespace\FooCommandHandler')->willReturn($this->handler->reveal());
        $this->container->get('Namespace\BarCommandHandler')->willReturn($this->handler->reveal());

        $this->parser->parse(Argument::any())->willReturn($this->command->reveal());

        $this->subject = new CommandRunner(
            $this->container->reveal(),
            [
                'foo' => 'Namespace\FooCommand',
                'bar' => 'Namespace\BarCommand',
            ]
        );
    }

    /** @test */
    public function it_fetches_the_parser_from_the_container()
    {
        $this->subject->run(SlackHandle::fromString('tom'), 'foo command');

        $this->container->get('Namespace\FooCommandParser')->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_parses_the_command()
    {
        $this->subject->run(SlackHandle::fromString('tom'), 'bar command arguments');

        $this->parser->parse('command arguments')->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_fetches_the_command_handler()
    {
        $this->subject->run(SlackHandle::fromString('tom'), 'foo command');

        $this->container->get('Namespace\FooCommandHandler')->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_handles_the_command()
    {
        $this->subject->run(SlackHandle::fromString('tom'), 'foo command');

        $this->handler->handle(SlackHandle::fromString('tom'), $this->command)->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_returns_the_command_handler_response()
    {
        $result = ['the result'];
        $this->handler->handle(Argument::cetera())->willReturn($result);

        assertSame($result, $this->subject->run(SlackHandle::fromString('tom'), 'foo command'));
    }
}
