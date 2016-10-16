<?php

namespace TomPHP\TimeTracker\Common;

trait Id
{
    /** @var string */
    private $value;

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
}
