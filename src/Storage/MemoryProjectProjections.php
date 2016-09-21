<?php

namespace TomPHP\TimeTracker\Storage;

use TomPHP\TimeTracker\Domain\Period;
use TomPHP\TimeTracker\Domain\ProjectId;
use TomPHP\TimeTracker\Domain\ProjectProjection;
use TomPHP\TimeTracker\Domain\ProjectProjections;

final class MemoryProjectProjections implements ProjectProjections
{
    /** @var ProjectProjection[] */
    private $projections = [];

    public function all() : array
    {
        return array_values($this->projections);
    }

    public function add(ProjectProjection $project)
    {
        $this->projections[(string) $project->projectId] = $project;
    }

    public function withId(ProjectId $id) : ProjectProjection
    {
        return $this->projections[(string) $id];
    }

    public function updateTotalTimeFor(ProjectId $id, Period $totalTime)
    {
        $project = $this->withId($id);

        $this->projections[(string) $id] = new ProjectProjection(
            $project->projectId,
            $project->projectName,
            $totalTime
        );
    }
}
