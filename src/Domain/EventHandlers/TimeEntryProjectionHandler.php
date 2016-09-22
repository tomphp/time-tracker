<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Domain\EventHandlers;

use TomPHP\TimeTracker\Domain\EventHandler;
use TomPHP\TimeTracker\Domain\Events\TimeEntryLogged;
use TomPHP\TimeTracker\Domain\TimeEntryProjection;
use TomPHP\TimeTracker\Domain\TimeEntryProjections;

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
