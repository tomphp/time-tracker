<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Domain;

final class Period
{
    /** @var int */
    private $hours;

    /** @var int */
    private $minutes;

    public static function fromString(string $string) : self
    {
        $parts = explode(':', $string);

        if (count($parts) == 1) {
            array_push($parts, 0);
        }

        list($hours, $minutes) = $parts;

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
}
