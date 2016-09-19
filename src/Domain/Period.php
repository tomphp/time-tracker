<?php

namespace TomPHP\TimeTracker\Domain;

final class Period
{
    public static function fromString(string $string) : self
    {
        return new self();
    }
}
