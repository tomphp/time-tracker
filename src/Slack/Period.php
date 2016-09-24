<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

final class Period
{
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
        $parts = explode(':', $string);

        $hours   = (int) array_shift($parts);
        $minutes = (int) array_shift($parts);

        return new self($hours, $minutes);
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

    public function __toString() : string
    {
        return sprintf('%d:%02d hours', $this->hours, $this->minutes);
    }
}
