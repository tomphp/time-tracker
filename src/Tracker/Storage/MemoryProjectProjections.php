<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjection;
use TomPHP\TimeTracker\Tracker\ProjectProjections;

final class MemoryProjectProjections implements ProjectProjections
{
    /** @var ProjectProjection[] */
    private $projectionsById = [];

    /** @var ProjectProjection[] */
    private $projectionsByName = [];

    public function all() : array
    {
        return array_values($this->projectionsById);
    }

    public function add(ProjectProjection $project)
    {
        $this->projectionsById[(string) $project->id()]        = $project;
        $this->projectionsByName[$project->name()]             = $project;
    }

    public function withId(ProjectId $id) : ProjectProjection
    {
        return $this->projectionsById[(string) $id];
    }

    public function withName(string $name) : ProjectProjection
    {
        return $this->projectionsByName[$name];
    }

    public function updateTotalTimeFor(ProjectId $id, Period $totalTime)
    {
        $project = $this->withId($id);

        $this->projectionsById[(string) $id] = new ProjectProjection(
            $project->id(),
            $project->name(),
            $totalTime
        );
    }
}
