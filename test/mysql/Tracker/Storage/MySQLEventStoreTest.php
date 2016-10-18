<?php declare(strict_types=1);

namespace test\mysql\TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use test\mysql\TomPHP\TimeTracker\MySQLConnection;
use test\support\MockAggregateId;
use test\support\MockEvent;
use TomPHP\TimeTracker\Tracker\EventStore;
use TomPHP\TimeTracker\Tracker\Storage\MySQLEventStore;

final class MySQLEventStoreTest extends \PHPUnit_Framework_TestCase
{
    use MySQLConnection;

    /** @var MySQLEventStore */
    private $eventStore;

    protected function setUp()
    {
        $this->clearTable('tracker_events');

        $this->eventStore = new MySQLEventStore($this->pdo());
    }

    /** @test */
    public function it_is_an_EventStore()
    {
        assertInstanceOf(EventStore::class, $this->eventStore);
    }

    /** @test */
    public function on_fetchByAggregateId_it_returns_all_events_with_that_aggregate_id()
    {
        $id1 = MockAggregateId::generate();
        $id2 = MockAggregateId::generate();

        $event1 = new MockEvent($id1, 'event1');
        $event2 = new MockEvent($id2, 'event2');
        $event3 = new MockEvent($id1, 'event3');

        $this->eventStore->store($event1);
        $this->eventStore->store($event2);
        $this->eventStore->store($event3);

        $events = $this->eventStore->fetchByAggregateId($id1);

        assertEquals([$event1, $event3], $events);
    }
}
