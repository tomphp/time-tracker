<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

abstract class Event
{
    abstract public function aggregateId() : AggregateId;

    abstract public function aggregateName() : string;
}
