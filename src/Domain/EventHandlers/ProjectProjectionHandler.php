<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Domain\EventHandlers;

use TomPHP\TimeTracker\Domain\EventHandler;
use TomPHP\TimeTracker\Domain\Events\ProjectCreated;
use TomPHP\TimeTracker\Domain\Events\TimeEntryLogged;
use TomPHP\TimeTracker\Domain\Period;
use TomPHP\TimeTracker\Domain\ProjectProjection;
use TomPHP\TimeTracker\Domain\ProjectProjections;

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
            $event->projectId,
            $event->projectName,
            Period::fromString('0:00')
        ));
    }

    protected function handleTimeEntryLogged(TimeEntryLogged $event)
    {
        $project = $this->projectProjections->withId($event->projectId());

        $this->projectProjections->updateTotalTimeFor(
            $event->projectId(),
            $project->totalTime->add($event->period())
        );
    }
}
