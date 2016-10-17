<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\EventHandlers;

use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\EventHandler;
use TomPHP\TimeTracker\Tracker\EventStore;

final class EventStoreHandler extends EventHandler
{
    /** @var EventStore */
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function handle(Event $event)
    {
        $this->eventStore->store($event);
    }
}
