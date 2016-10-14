<?php

namespace test\unit\TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\Events\TimeEntryLogged;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntry;
use TomPHP\TimeTracker\Tracker\TimeEntryId;

final class TimeEntryLoggedTest extends \PHPUnit_Framework_TestCase
{
    const TIME_ENTRY_ID = 'example-time-entry-id';

    protected function event() : Event
    {
        return new TimeEntryLogged(
            TimeEntryId::fromString(self::TIME_ENTRY_ID),
            DeveloperId::generate(),
            ProjectId::generate(),
            Date::today(),
            Period::fromString('10'),
            'Entry description'
        );
    }

    /** @test */
    public function it_exposes_its_aggregate_type()
    {
        assertSame(TimeEntry::class, $this->event()->aggregateName());
    }

    /** @test */
    public function it_exposes_the_aggregate_id()
    {
        assertEquals(TimeEntryId::fromString(self::TIME_ENTRY_ID), $this->event()->aggregateId());
    }
}
