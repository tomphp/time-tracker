<?php

namespace test\unit\TomPHP\TimeTracker\Tracker\EventHandlers;

use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\EventHandler;
use TomPHP\TimeTracker\Tracker\EventHandlers\EventStoreHandler;
use TomPHP\TimeTracker\Tracker\EventStore;

final class EventStoreHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_is_an_event_handler()
    {
        assertInstanceOf(
            EventHandler::class,
            new EventStoreHandler($this->prophesize(EventStore::class)->reveal())
        );
    }

    /** @test */
    public function on_handle_stores_the_event_to_the_event_store()
    {
        $store   = $this->prophesize(EventStore::class);
        $handler = new EventStoreHandler($store->reveal());
        $event   = $this->prophesize(Event::class);

        $handler->handle($event->reveal());

        $store->store($event)->shouldHaveBeenCalled();
    }
}
