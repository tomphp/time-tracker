<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\AggregateId;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\Events\TimeEntryLogged;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntry;
use TomPHP\TimeTracker\Tracker\TimeEntryId;

final class TimeEntryLoggedTest extends AbstractEventTest
{
    const TIME_ENTRY_ID = 'example-time-entry-id';

    protected function event() : Event
    {
        return new TimeEntryLogged(
            $this->aggregateId(),
            DeveloperId::generate(),
            ProjectId::generate(),
            Date::today(),
            Period::fromString('10'),
            'Entry description'
        );
    }

    protected function aggregateId() : AggregateId
    {
        return TimeEntryId::fromString(self::TIME_ENTRY_ID);
    }

    protected function aggregateName() : string
    {
        return TimeEntry::class;
    }
}
