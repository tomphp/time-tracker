<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Slack\Command\LinkCommand;
use TomPHP\TimeTracker\Slack\Command\LinkCommandParser;

final class LinkCommandParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var LinkCommandParser */
    private $subject;

    protected function setUp()
    {
        $this->subject = new LinkCommandParser();
    }

    /** @test */
    public function it_parses_the_link_command()
    {
        $command = $this->subject->parse('to account mike@rgsoftware.com');

        assertEquals(
            new LinkCommand(Email::fromString('mike@rgsoftware.com')),
            $command
        );
    }
}
