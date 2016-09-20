<?php

namespace TomPHP\TimeTracker\Domain;

use Ramsey\Uuid\Uuid;

final class ProjectId
{
    /** @var Uuid */
    private $value;

    public static function generate() : self
    {
        return new self(Uuid::uuid4());
    }

    public function __construct(Uuid $value)
    {
        $this->value = $value;
    }

    public function __toString() : string
    {
        return (string) $this->value;
    }
}
