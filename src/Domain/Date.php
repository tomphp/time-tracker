<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Domain;

final class Date
{
    public static function fromString(string $string) : self
    {
        return new self();
    }
}
