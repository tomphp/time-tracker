<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\EventHandlers;

use TomPHP\TimeTracker\Tracker\EventHandler;
use TomPHP\TimeTracker\Tracker\Events\TimeEntryLogged;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

final class TimeEntryProjectionHandler extends EventHandler
{
    /** @var TimeEntryProjections */
    private $timeEntryProjections;

    public function __construct(TimeEntryProjections $timeEntrieProjections)
    {
        $this->timeEntryProjections = $timeEntrieProjections;
    }

    protected function handleTimeEntryLogged(TimeEntryLogged $event)
    {
        $this->timeEntryProjections->add(new TimeEntryProjection(
            $event->aggregateId(),
            $event->developerId(),
            $event->projectId(),
            $event->date(),
            $event->period(),
            $event->description()
        ));
    }
}
