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

final class CommandRunnerTest extends \PHPUnit_Framework_TestCase
{
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

        $this->userId = SlackUserId::fromString('U1234567890');

        $this->subject = new CommandRunner(
            $this->container->reveal(),
            $this->sanitiser->reveal(),
            [
                'foo' => 'Namespace\FooCommand',
                'bar' => 'Namespace\BarCommand',
            ]
        );
    }

    /** @test */
    public function it_fetches_the_parser_from_the_container()
    {
        $this->sanitiser->sanitise('foo command')->willReturn('foo command');

        $this->subject->run($this->userId, 'foo command');

        $this->container->get('Namespace\FooCommandParser')->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_parses_the_command()
    {
        $this->sanitiser->sanitise('bar command arguments')->willReturn('bar command arguments sanitised');

        $this->subject->run($this->userId, 'bar command arguments');

        $this->parser->parse('command arguments sanitised')->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_fetches_the_command_handler()
    {
        $this->sanitiser->sanitise('foo command')->willReturn('foo command');

        $this->subject->run($this->userId, 'foo command');

        $this->container->get('Namespace\FooCommandHandler')->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_handles_the_command()
    {
        $this->sanitiser->sanitise('foo command')->willReturn('foo command');

        $this->subject->run($this->userId, 'foo command');

        $this->handler->handle($this->userId, $this->command)->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_returns_the_command_handler_response()
    {
        $result = ['the result'];
        $this->handler->handle(Argument::cetera())->willReturn($result);
        $this->sanitiser->sanitise('foo command')->willReturn('foo command');

        assertSame($result, $this->subject->run($this->userId, 'foo command'));
    }
}
