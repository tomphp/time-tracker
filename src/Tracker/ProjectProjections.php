<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\Period;

interface ProjectProjections
{
    /** @return ProjectProjection */
    public function all() : array;

    /** @return void */
    public function add(ProjectProjection $project);

    public function withId(ProjectId $id) : ProjectProjection;

    public function withName(string $name) : ProjectProjection;

    /** @return void */
    public function updateTotalTimeFor(ProjectId $id, Period $totalTime);
}
