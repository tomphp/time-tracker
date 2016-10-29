<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Common;

final class Period
{
    const REGEX = '(?:'
            . '(?:(?<hours>\d+)\s*(?:hours?|hrs|hr|h))?\s*(?:(?<minutes>\d+)\s*(minutes?|mins?|m))?'
            . '|'
            . '(?<compound>\d:\d\d)'
            . '|'
            . '(?<decimal>\d?\.\d+)'
            . '|'
            . '(?<single>\d)'
        . ')';

    /** @var int */
    private $hours;

    /** @var int */
    private $minutes;

    public static function fromHours(int $hours) : self
    {
        return new self($hours, 0);
    }

    public static function fromString(string $string) : self
    {
        if (!preg_match('/^' . self::REGEX . '$/', $string, $parts)) {
        }

        if (isset($parts['single']) && $parts['single']) {
            $hours   = $parts['single'];
            $minutes = 0;
        } elseif (isset($parts['decimal']) && $parts['decimal']) {
            $hours   = floor($parts['decimal']);
            $minutes = ($parts['decimal'] - $hours) * 60;
        } elseif (isset($parts['compound']) && $parts['compound']) {
            list($hours, $minutes) = explode(':', $parts['compound'], 2);
        } else {
            $hours   = $parts['hours'] ?? 0;
            $minutes = $parts['minutes'] ?? 0;
        }

        return new self((int) $hours, (int) $minutes);
    }

    private function __construct(int $hours, int $minutes)
    {
        $this->hours   = $hours;
        $this->minutes = $minutes;
    }

    public function hours() : int
    {
        return $this->hours;
    }

    public function minutes() : int
    {
        return $this->minutes;
    }

    public function add(self $other) : self
    {
        $hours   = $this->hours + $other->hours();
        $minutes = $this->minutes + $other->minutes();

        if ($minutes >= 60) {
            $minutes -= 60;
            $hours += 1;
        }

        return new self($hours, $minutes);
    }

    public function __toString() : string
    {
        if ($this->hours && $this->minutes) {
            return sprintf('%dh %02dm', $this->hours, $this->minutes);
        }

        if (!$this->hours && $this->minutes) {
            return sprintf('%02dm', $this->minutes);
        }

        return sprintf('%dh', $this->hours);
    }
}
