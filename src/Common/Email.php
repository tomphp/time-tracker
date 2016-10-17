<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Common;

final class Email
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
