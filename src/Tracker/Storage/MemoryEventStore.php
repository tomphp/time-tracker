<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\EventStore;

final class MemoryEventStore implements EventStore
{
    public function store(Event $event)
    {
    }
}
