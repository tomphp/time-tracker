<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Common;

final class Date
{
    const SECONDS_IN_MINUTE = 60;
    const MINUTES_IN_HOUR   = 60;
    const HOURS_IN_DAY      = 24;

    /** @var string */
    private $value;

    public static function today() : self
    {
        return new self(date('Y-m-d'));
    }

    public static function yesterday() : self
    {
        $secondsInDay = self::SECONDS_IN_MINUTE * self::MINUTES_IN_HOUR * self::HOURS_IN_DAY;
        $timestamp    = time() - $secondsInDay;

        return new self(date('Y-m-d', $timestamp));
    }

    public static function fromString(string $string) : self
    {
        return new self($string);
    }

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString() : string
    {
        return $this->value;
    }

    public function toFriendlyString() : string
    {
        if ($this == self::today()) {
            return 'today';
        }
        return 'yesterday';
    }
}
