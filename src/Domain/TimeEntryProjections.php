<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Domain;

interface TimeEntryProjections
{
    /** @return TimeEntry[] */
    public function forProject(ProjectId $projectId) : array;

    /** @return void */
    public function add(TimeEntryProjection $projection);
}
