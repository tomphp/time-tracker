<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Common;

use TomPHP\TimeTracker\Common\Email;

final class EmailTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_converted_from_and_to_a_string()
    {
        assertSame('tom@example.com', (string) Email::fromString('tom@example.com'));
    }
}
