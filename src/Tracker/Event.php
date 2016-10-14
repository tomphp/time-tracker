<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

abstract class Event
{
    abstract public static function fromParams(
        string $aggregateId,
        array $params
    ) : self;

    abstract public function aggregateId() : AggregateId;

    abstract public function aggregateName() : string;

    abstract public function params() : array;
}
