<?php declare(strict_types=1);

namespace test\features\TomPHP\TimeTracker;

use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Common\Period;

trait CommonTransforms
{
    /**
     * @Transform
     */
    public function castStringToPeriod(string $string) : Period
    {
        return Period::fromString($string);
    }

    /**
     * @Transform
     */
    public function castStringToDate(string $string) : Date
    {
        return Date::fromString($string);
    }

    /**
     * @Transform
     */
    public function castStringToEmail(string $string) : Email
    {
        return Email::fromString($string);
    }
}
