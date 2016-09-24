<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Slack\Command\LogCommand;
use TomPHP\TimeTracker\Slack\Command\LogCommandParser;
use TomPHP\TimeTracker\Slack\Date;
use TomPHP\TimeTracker\Slack\Period;

final class LogCommandParserTest extends \PHPUnit_Framework_TestCase
{
    /** @LogCommandParser */
    private $subject;

    protected function setUp()
    {
        $this->today = Date::today();

        $this->subject = new LogCommandParser(Date::today());
    }

    /** @test */
    public function it_parses_a_simple_command_with_no_date()
    {
        $command = $this->subject->parse('log 3hrs against Time Tracker for Implementing Slack integration');

        assertEquals(new LogCommand(
            'Time Tracker',
            Date::today(),
            Period::fromString('3'),
            'Implementing Slack integration'
        ), $command);
    }

    /** @test */
    public function it_throws_if_the_command_cannot_be_parsed()
    {
        $this->markTestIncomplete('Implement me!');
    }
}
