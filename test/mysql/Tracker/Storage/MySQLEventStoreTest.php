<?php

namespace test\mysql\TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use TomPHP\TimeTracker\Tracker\Storage\MySQLEventStore;
use TomPHP\TimeTracker\Tracker\EventStore;
use TomPHP\TimeTracker\Tracker\AggregateId;
use TomPHP\TimeTracker\Tracker\Event;
use test\support\MockEvent;
use test\support\MockAggregateId;

final class MySQLEventStoreTest extends \PHPUnit_Framework_TestCase
{
    /** @var MySQLEventStore */
    private $eventStore;

    protected function setUp()
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s', getenv('MYSQL_HOSTNAME'), getenv('MYSQL_DBNAME'));
        $pdo = new PDO($dsn, getenv('MYSQL_USERNAME'), getenv('MYSQL_PASSWORD'));

        $pdo->exec('TRUNCATE `tracker_events`');

        $this->eventStore = new MySQLEventStore($pdo);
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
