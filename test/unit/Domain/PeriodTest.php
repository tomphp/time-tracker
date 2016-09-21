<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Domain;

use TomPHP\TimeTracker\Domain\Period;

final class PeriodTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_from_string_it_splits_minutes_and_hours()
    {
        $period = Period::fromString('1:11');

        assertSame(1, $period->hours());
        assertSame(11, $period->minutes());
    }

    /** @test */
    public function on_from_string_it_is_hours_if_no_colon_is_present()
    {
        $period = Period::fromString('5');

        assertSame(5, $period->hours());
        assertSame(0, $period->minutes());
    }

    /** @test */
    public function on_add_it_adds_the_periods_together()
    {
        $period = Period::fromString('1:10')->add(Period::fromString('2:20'));

        assertEquals(Period::fromString('3:30'), $period);
    }

    /** @test */
    public function on_add_it_rolls_over_the_minutes_if_they_go_over_60()
    {
        $period = Period::fromString('1:50')->add(Period::fromString('0:20'));

        assertEquals(Period::fromString('2:10'), $period);
    }
}
