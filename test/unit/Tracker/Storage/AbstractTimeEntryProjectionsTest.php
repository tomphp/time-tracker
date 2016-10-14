<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntryId;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

abstract class AbstractTimeEntryProjectionsTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function timeEntries() : TimeEntryProjections;

    /** @test */
    public function on_forProject_it_returns_all_added_projections_for_that_project()
    {
        $projectId = ProjectId::generate();

        $projection1 = new TimeEntryProjection(
            TimeEntryId::generate(),
            DeveloperId::generate(),
            $projectId,
            Date::fromString('2016-09-19'),
            Period::fromString('0'),
            'Example entry 1'
        );
        $projection2 = new TimeEntryProjection(
            TimeEntryId::generate(),
            DeveloperId::generate(),
            ProjectId::generate(),
            Date::fromString('2016-09-21'),
            Period::fromString('0'),
            'Example entry 2'
        );

        $this->timeEntries()->add($projection1);
        $this->timeEntries()->add($projection2);

        assertEquals([$projection1], $this->timeEntries()->forProject($projectId));
    }
}
