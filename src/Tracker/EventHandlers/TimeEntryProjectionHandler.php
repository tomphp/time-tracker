<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\EventHandlers;

use TomPHP\TimeTracker\Tracker\EventHandler;
use TomPHP\TimeTracker\Tracker\Events\TimeEntryLogged;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

final class TimeEntryProjectionHandler extends EventHandler
{
    /** @var TimeEntryProjections */
    private $timeEntrieProjections;

    public function __construct(TimeEntryProjections $timeEntrieProjections)
    {
        $this->timeEntrieProjections = $timeEntrieProjections;
    }

    protected function handleTimeEntryLogged(TimeEntryLogged $event)
    {
        $this->timeEntrieProjections->add(new TimeEntryProjection(
            $event->userId(),
            $event->projectId(),
            $event->date(),
            $event->period(),
            $event->description()
        ));
    }
}
