<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack\Exception;

use TomPHP\TimeTracker\Slack\Exception\CommandFormatInvalid;

final class CommandFormatInvalidTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_formats_the_message()
    {
        $exception = new CommandFormatInvalid('ParserName', 'command string');

        assertEquals(
            'Invalid command "command string" for ParserName.',
            $exception->getMessage()
        );
    }
}
