<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Slack\CommandSanitiser;

final class CommandSanitiserTest extends \PHPUnit_Framework_TestCase
{
    /** @var CommandSanitiser */
    private $sanitiser;

    protected function setUp()
    {
        $this->sanitiser = new CommandSanitiser();
    }

    /** @test */
    public function it_returns_as_sane_command_unchanged()
    {
        assertSame('example sane command', $this->sanitiser->sanitise('example sane command'));
    }

    /** @test */
    public function it_removes_excess_white_space()
    {
        assertSame('too much space', $this->sanitiser->sanitise('too   much  space'));
    }

    /** @test */
    public function it_trims_leading_and_trailing_whitespace()
    {
        assertSame('in the middle', $this->sanitiser->sanitise('   in the middle  '));
    }
}
