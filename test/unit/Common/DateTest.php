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

    /** @test */
    public function on_yesterday_it_creates_an_instance_with_yesterdays_date()
    {
        $timestamp = time() - 60 * 60 * 24;

        assertSame(date('Y-m-d', $timestamp), (string) Date::yesterday());
    }

    /** @test */
    public function on_toFriendlyString_it_returns_today_for_todays_date()
    {
        assertSame('today', Date::today()->toFriendlyString());
    }

    /** @test */
    public function on_toFriendlyString_it_returns_yesterday_for_yesterdays_date()
    {
        assertSame('yesterday', Date::yesterday()->toFriendlyString());
    }
}
