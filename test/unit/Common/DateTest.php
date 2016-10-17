<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Common;

use TomPHP\TimeTracker\Common\Date;

final class DateTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_converted_from_and_to_a_string()
    {
        assertSame('2016-10-05', (string) Date::fromString('2016-10-05'));
    }

    /** @test */
    public function on_today_it_creates_an_instance_with_todays_date()
    {
        assertSame(date('Y-m-d'), (string) Date::today());
    }
}
