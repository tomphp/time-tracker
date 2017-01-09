<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Slack\Command\LinkCommand;
use TomPHP\TimeTracker\Slack\Command\LinkCommandParser;
use TomPHP\TimeTracker\Slack\Exception\CommandFormatInvalid;

final class LinkCommandParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var LinkCommandParser */
    private $subject;

    protected function setUp()
    {
        $this->subject = new LinkCommandParser();
    }

    /** @test */
    public function on_parse_it_throws_if_the_command_cannot_be_parsed()
    {
        $this->expectException(CommandFormatInvalid::class);

        $this->subject->parse('incorrect format of the command');
    }

    /** @test */
    public function on_parse_it_parses_the_link_command()
    {
        $command = $this->subject->parse('to account mike@rgsoftware.com');

        assertEquals(
            new LinkCommand(Email::fromString('mike@rgsoftware.com')),
            $command
        );
    }

    /** @test */
    public function on_formatDescription_it_returns_the_command_description()
    {
        assertEquals(
            'link to account [email address]',
            $this->subject->formatDescription()
        );
    }
}
