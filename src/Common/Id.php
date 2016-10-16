<?php

namespace TomPHP\TimeTracker\Common;

trait Id
{
    /** @var string */
    private $value;

    /** @return static */
    final public static function fromString(string $string)
    {
        return new static($string);
    }

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    final public function __toString() : string
    {
        return $this->value;
    }
}
