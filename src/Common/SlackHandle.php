<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Common;

final class SlackHandle
{
    /** @var string */
    private $value;

    public static function fromString(string $string) : SlackHandle
    {
        return new self($string);
    }

    private function __construct(string $value)
    {
        $this->value = preg_replace('/^@?(.*)$/', '\1', $value);
    }

    public function value() : string
    {
        return $this->value;
    }

    public function __toString() : string
    {
        return '@' . $this->value;
    }
}
