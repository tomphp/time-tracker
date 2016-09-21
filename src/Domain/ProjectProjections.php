<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Domain;

interface ProjectProjections
{
    /** @return ProjectProjection */
    public function all() : array;

    /** @return void */
    public function add(ProjectProjection $project);

    public function withId(ProjectId $id) : ProjectProjection;

    /** @return void */
    public function updateTotalTimeFor(ProjectId $id, Period $totalTime);
}
