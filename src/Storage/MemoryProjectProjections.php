<?php

namespace TomPHP\TimeTracker\Storage;

use TomPHP\TimeTracker\Domain\ProjectProjection;
use TomPHP\TimeTracker\Domain\ProjectProjections;

final class MemoryProjectProjections implements ProjectProjections
{
    /** @var ProjectProjection[] */
    private $projections = [];

    public function all() : array
    {
        return $this->projections;
    }

    public function add(ProjectProjection $project)
    {
        $this->projections[] = $project;
    }
}
