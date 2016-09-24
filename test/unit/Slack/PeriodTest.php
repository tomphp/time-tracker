<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Slack\Period;

final class PeriodTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_fromString_it_constructs_from_a_colon_separated_string()
    {
        $period = Period::fromString('2:45');

        assertSame(2, $period->hours());
        assertSame(45, $period->minutes());
    }

    /** @test */
    public function on_fromString_it_constructs_from_a_single_number_string()
    {
        $period = Period::fromString('3');

        assertSame(3, $period->hours());
        assertSame(0, $period->minutes());
    }

    /** @test */
    public function on_fromHours_it_constructs_from_an_integer()
    {
        $period = Period::fromHours(2);

        assertSame(2, $period->hours());
        assertSame(0, $period->minutes());
    }

    /** @test */
    public function on_toString_it_returns_a_string_representation()
    {
        assertSame('2:45 hours', (string) Period::fromString('2:45'));
    }
}
