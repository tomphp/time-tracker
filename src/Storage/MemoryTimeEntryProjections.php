<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Storage;

use TomPHP\TimeTracker\Domain\ProjectId;
use TomPHP\TimeTracker\Domain\TimeEntryProjection;
use TomPHP\TimeTracker\Domain\TimeEntryProjections;

final class MemoryTimeEntryProjections implements TimeEntryProjections
{
    /** @var TimeEntryProjection[] */
    private $projections = [];

    public function forProject(ProjectId $projectId) : array
    {
        return array_values(array_filter(
            $this->projections,
            function (TimeEntryProjection $projection) use ($projectId) {
                return $projectId == $projection->projectId();
            }
        ));
    }

    public function add(TimeEntryProjection $projection)
    {
        $this->projections[] = $projection;
    }
}
