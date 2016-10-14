<?php

namespace test\unit\TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Tracker\AggregateId;
use TomPHP\TimeTracker\Tracker\Event;

abstract class AbstractEventTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function event() : Event;

    abstract protected function aggregateName() : string;

    abstract protected function aggregateId() : AggregateId;

    /** @test */
    public function it_exposes_its_aggregate_type()
    {
        assertSame($this->aggregateName(), $this->event()->aggregateName());
    }

    /** @test */
    public function it_exposes_the_aggregate_id()
    {
        assertEquals($this->aggregateId(), $this->event()->aggregateId());
    }
}
