<?php

namespace test\unit\TomPHP\TimeTracker\Common;

use TomPHP\TimeTracker\Common\Date;

final class DateTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_converted_from_and_to_a_string()
    {
        assertSame('2016-10-05', (string) Date::fromString('2016-10-05'));
    }
}
