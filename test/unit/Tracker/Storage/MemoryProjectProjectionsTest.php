<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Storage;

use TomPHP\TimeTracker\Tracker\Period;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjection;
use TomPHP\TimeTracker\Tracker\Storage\MemoryProjectProjections;

final class MemoryProjectProjectionsTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_all_it_returns_all_added_project_projections()
    {
        $projects = new MemoryProjectProjections();

        $project1 = new ProjectProjection(ProjectId::generate(), 'Project One', Period::fromString('0'));
        $project2 = new ProjectProjection(ProjectId::generate(), 'Project Two', Period::fromString('0'));

        $projects->add($project1);
        $projects->add($project2);

        assertSame([$project1, $project2], $projects->all());
    }

    /** @test */
    public function on_withId_it_returns_the_project_projection_with_that_id()
    {
        $projects = new MemoryProjectProjections();

        $projectId = ProjectId::generate();
        $project   = new ProjectProjection($projectId, 'Project One', Period::fromString('0'));

        $projects->add($project);

        assertSame($project, $projects->withId($projectId));
    }

    /** @test */
    public function on_withId_it_throws_if_there_is_no_project_projection_for_the_given_id()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function on_updateTotalTimeFor_it_updates_the_total_time_for_the_given_project()
    {
        $projects = new MemoryProjectProjections();

        $projectId = ProjectId::generate();
        $project   = new ProjectProjection($projectId, 'Project One', Period::fromString('0'));

        $projects->add($project);

        $projects->updateTotalTimeFor($projectId, Period::fromString('8'));

        assertEquals(Period::fromString('8'), $projects->withId($projectId)->totalTime());
    }

    /** @test */
    public function on_updateTotalTimeFor_it_throws_if_there_is_no_project_projection_for_the_given_id()
    {
        $this->markTestIncomplete();
    }
}
