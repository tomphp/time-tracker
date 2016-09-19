<?php

namespace TomPHP\TimeTracker\Domain\EventHandlers;

use TomPHP\TimeTracker\Domain\EventHandler;
use TomPHP\TimeTracker\Domain\Events\ProjectCreated;
use TomPHP\TimeTracker\Domain\ProjectProjections;
use TomPHP\TimeTracker\Domain\ProjectProjection;

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
            $event->projectName
        ));
    }
}
