<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Storage;

use TomPHP\TimeTracker\Tracker\Date;
use TomPHP\TimeTracker\Tracker\Period;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\Storage\MemoryTimeEntryProjections;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\UserId;

final class MemoryTimeEntryProjectionsTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_forProject_it_returns_all_added_projections_for_that_project()
    {
        $projects = new MemoryTimeEntryProjections();

        $projectId = ProjectId::generate();

        $projection1 = new TimeEntryProjection(
            UserId::generate(),
            $projectId,
            Date::fromString('2016-09-19'),
            Period::fromString('0'),
            'Example entry 1'
        );
        $projection2 = new TimeEntryProjection(
            UserId::generate(),
            ProjectId::generate(),
            Date::fromString('2016-09-21'),
            Period::fromString('0'),
            'Example entry 2'
        );

        $projects->add($projection1);
        $projects->add($projection2);

        assertSame([$projection1], $projects->forProject($projectId));
    }
}
