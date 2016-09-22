<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Tracker\Period;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjection;
use TomPHP\TimeTracker\Tracker\ProjectProjections;

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
        $this->projections[(string) $project->projectId()] = $project;
    }

    public function withId(ProjectId $id) : ProjectProjection
    {
        return $this->projections[(string) $id];
    }

    public function updateTotalTimeFor(ProjectId $id, Period $totalTime)
    {
        $project = $this->withId($id);

        $this->projections[(string) $id] = new ProjectProjection(
            $project->projectId(),
            $project->name(),
            $totalTime
        );
    }
}
