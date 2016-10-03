<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\EventHandlers;

use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\EventHandler;
use TomPHP\TimeTracker\Tracker\Events\ProjectCreated;
use TomPHP\TimeTracker\Tracker\Events\TimeEntryLogged;
use TomPHP\TimeTracker\Tracker\ProjectProjection;
use TomPHP\TimeTracker\Tracker\ProjectProjections;

final class ProjectProjectionHandler extends EventHandler
{
    /** @var ProjectProjections */
    private $projectProjections;

    public function __construct(ProjectProjections $projectProjections)
    {
        $this->projectProjections = $projectProjections;
    }

    protected function handleProjectCreated(ProjectCreated $event)
    {
        $this->projectProjections->add(new ProjectProjection(
            $event->projectId(),
            $event->projectName(),
            Period::fromString('0:00')
        ));
    }

    protected function handleTimeEntryLogged(TimeEntryLogged $event)
    {
        $project = $this->projectProjections->withId($event->projectId());

        $this->projectProjections->updateTotalTimeFor(
            $event->projectId(),
            $project->totalTime()->add($event->period())
        );
    }
}
