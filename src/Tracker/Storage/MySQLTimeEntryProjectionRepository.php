<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntryId;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

final class MySQLTimeEntryProjectionRepository implements TimeEntryProjections
{
    use MySQLTools;

    const TABLE_NAME = 'time_entry_projections';

    public function forProject(ProjectId $projectId) : array
    {
        return $this->selectWhere('projectId', (string) $projectId);
    }

    public function add(TimeEntryProjection $projection)
    {
        $this->insert($projection);
    }

    /** @return TimeEntryProjection */
    protected function create(\stdClass $row)
    {
        return new TimeEntryProjection(
            TimeEntryId::fromString($row->id),
            DeveloperId::fromString($row->developerId),
            ProjectId::fromString($row->projectId),
            Date::fromString($row->date),
            Period::fromString($row->period),
            $row->description
        );
    }

    /**
     * @param TimeEntryProjection $projection
     */
    protected function extract($projection) : array
    {
        return [
            'id'          => (string) $projection->id(),
            'projectId'   => (string) $projection->projectId(),
            'developerId' => (string) $projection->developerId(),
            'date'        => (string) $projection->date(),
            'period'      => (string) $projection->period(),
            'description' => $projection->description(),
        ];
    }
}
