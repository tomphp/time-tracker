<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Common;

use TomPHP\TimeTracker\Common\Period;

final class PeriodTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_fromString_a_single_number_is_hours()
    {
        $this->assertFromStringWorks('5:00', '5');
    }

    /** @test */
    public function on_fromString_it_parsess_the_format_2h()
    {
        $this->assertFromStringWorks('2:00', '2h');
    }

    /** @test */
    public function on_fromString_it_parses_the_format_4hr()
    {
        $this->assertFromStringWorks('4:00', '4hr');
    }

    /** @test */
    public function on_fromString_it_parses_the_format_5hrs()
    {
        $this->assertFromStringWorks('5:00', '5hrs');
    }

    /** @test */
    public function on_fromString_it_parses_the_format_1hour()
    {
        $this->assertFromStringWorks('1:00', '1hour');
    }

    /** @test */
    public function on_fromString_it_parses_the_format_3hours()
    {
        $this->assertFromStringWorks('3:00', '3hours');
    }

    /** @test */
    public function on_fromString_it_parses_the_format_30m()
    {
        $this->assertFromStringWorks('0:30', '30m');
    }

    /** @test */
    public function on_fromString_it_parses_the_format_11min()
    {
        $this->assertFromStringWorks('0:11', '11min');
    }

    /** @test */
    public function on_fromString_it_parses_the_format_15mins()
    {
        $this->assertFromStringWorks('0:15', '15mins');
    }

    /** @test */
    public function on_fromString_it_parses_the_format_1h5m()
    {
        $this->assertFromStringWorks('1:05', '1h5m');
    }

    /** @test */
    public function on_fromString_it_parses_the_format_1hr5mins()
    {
        $this->assertFromStringWorks('1:05', '1hr5mins');
    }

    /** @test */
    public function on_fromString_it_parses_the_format_3_colon_45()
    {
        $this->assertFromStringWorks('3:45', '3:45');
    }

    /** @test */
    public function on_fromString_it_parses_the_format_2_point_75()
    {
        $this->assertFromStringWorks('2:45', '2.75');
    }

    /** @test */
    public function on_fromString_it_parses_the_format_point_5()
    {
        $this->assertFromStringWorks('0:30', '.5');
    }

    /** @test */
    public function on_fromString_it_works_with_whitespace()
    {
        $this->assertFromStringWorks('2:00', '2 h');
        $this->assertFromStringWorks('4:00', '4 hr');
        $this->assertFromStringWorks('5:00', '5 hrs');
        $this->assertFromStringWorks('1:00', '1 hour');
        $this->assertFromStringWorks('3:00', '3 hours');
        $this->assertFromStringWorks('0:30', '30 m');
        $this->assertFromStringWorks('0:11', '11 min');
        $this->assertFromStringWorks('0:15', '15 mins');
        $this->assertFromStringWorks('1:05', '1 h 5 m');
        $this->assertFromStringWorks('1:05', '1 hr 5 mins');
    }

    /** @test */
    public function on_fromString_it_handles_multiple_digits()
    {
        $this->assertFromStringWorks('21:00', '21 h');
        $this->assertFromStringWorks('45:00', '45 hr');
        $this->assertFromStringWorks('51:00', '51 hrs');
        $this->assertFromStringWorks('19:00', '19 hour');
        $this->assertFromStringWorks('32:00', '32 hours');
    }

    /** @test */
    public function on_fromHours_it_constructs_from_an_integer()
    {
        $period = Period::fromHours(2);

        assertSame(2, $period->hours());
        assertSame(0, $period->minutes());
    }

    /** @test */
    public function on_toString_it_returns_a_string_for_hours_only()
    {
        assertSame('2h', (string) Period::fromString('2:00'));
    }

    /** @test */
    public function on_toString_it_returns_a_string_for_minutes_only()
    {
        assertSame('30m', (string) Period::fromString('0:30'));
    }

    /** @test */
    public function on_toString_it_returns_a_string_for_hours_and_minutes()
    {
        assertSame('2h 45m', (string) Period::fromString('2:45'));
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

    private function assertFromStringWorks(string $expected, string $input)
    {
        if (!preg_match('/^(\d+):(\d\d)$/', $expected, $parts)) {
            throw new \LogicException("$expected does not match format h:mm");
        }

        $hours = (int) $parts[1];
        $mins  = (int) $parts[2];

        $period = Period::fromString($input);

        $this->assertRegexMatches($input);
        assertSame($hours, $period->hours());
        assertSame($mins, $period->minutes());
    }

    private function assertRegexMatches(string $string)
    {
        $regex = Period::REGEX;

        $result = preg_replace("/^before ($regex) after$/", '\1', "before $string after");

        assertSame($string, $result, 'Regex match failed');
    }
}
