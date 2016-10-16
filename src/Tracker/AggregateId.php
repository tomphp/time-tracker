<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

interface AggregateId
{
    public function __toString() : string;
}
