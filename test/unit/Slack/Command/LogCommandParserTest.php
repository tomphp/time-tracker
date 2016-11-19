<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Slack\Command\LogCommand;
use TomPHP\TimeTracker\Slack\Command\LogCommandParser;

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
    public function on_matchesFormat_it_returns_true_for_a_valid_format_command()
    {
        assertTrue($this->subject->matchesFormat('3hrs against Project for Description'));
    }

    /** @test */
    public function on_matchesFormat_it_returns_false_for_an_invalid_format_command()
    {
        assertFalse($this->subject->matchesFormat('invalid format command'));
    }

    /** @test */
    public function on_parse_it_parses_a_simple_command_with_no_date()
    {
        $commandString = '3hrs against Time Tracker for Implementing Slack integration';
        $command       = $this->subject->parse($commandString);

        assertTrue($this->subject->matchesFormat($commandString));
        assertEquals(new LogCommand(
            'Time Tracker',
            Date::today(),
            Period::fromString('3'),
            'Implementing Slack integration'
        ), $command);
    }

    /** @test */
    public function on_parse_it_parses_a_log_which_is_explicitly_for_today()
    {
        $commandString = '3hrs today against Time Tracker for Implementing Slack integration';
        $command       = $this->subject->parse($commandString);

        assertTrue($this->subject->matchesFormat($commandString));
        assertEquals(new LogCommand(
            'Time Tracker',
            Date::today(),
            Period::fromString('3'),
            'Implementing Slack integration'
        ), $command);
    }

    /** @test */
    public function on_parse_it_parses_a_log_command_for_yesterday()
    {
        $commandString = '3hrs yesterday against Time Tracker for Implementing Slack integration';
        $command       = $this->subject->parse($commandString);

        assertTrue($this->subject->matchesFormat($commandString));
        assertEquals(new LogCommand(
            'Time Tracker',
            Date::yesterday(),
            Period::fromString('3'),
            'Implementing Slack integration'
        ), $command);
    }

    /** @test */
    public function on_parse_it_throws_if_the_command_cannot_be_parsed()
    {
        $this->markTestIncomplete('Implement me!');
    }

    /** @test */
    public function on_formatDescription_it_returns_the_description()
    {
        assertSame('log [time] against [project] for [description]', $this->subject->formatDescription());
    }
}
