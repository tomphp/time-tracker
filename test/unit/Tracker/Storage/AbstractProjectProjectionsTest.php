<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjection;
use TomPHP\TimeTracker\Tracker\ProjectProjections;

abstract class AbstractProjectProjectionsTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function projects() : ProjectProjections;

    /** @test */
    public function on_all_it_returns_all_added_project_projections()
    {
        $project1 = new ProjectProjection(ProjectId::generate(), 'Project One', Period::fromString('0'));
        $project2 = new ProjectProjection(ProjectId::generate(), 'Project Two', Period::fromString('0'));

        $this->projects()->add($project1);
        $this->projects()->add($project2);

        assertEquals(
            $this->sortProjects([$project1, $project2]),
            $this->sortProjects($this->projects()->all())
        );
    }

    /** @test */
    public function on_withId_it_returns_the_project_projection_with_that_id()
    {
        $projectId = ProjectId::generate();
        $project   = new ProjectProjection($projectId, 'Project One', Period::fromString('0'));

        $this->projects()->add($project);

        assertEquals($project, $this->projects()->withId($projectId));
    }

    /** @test */
    public function on_withId_it_throws_if_there_is_no_project_projection_for_the_given_id()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function on_withName_it_returns_the_project_with_that_name()
    {
        $projectId = ProjectId::generate();
        $project   = new ProjectProjection($projectId, 'Project One', Period::fromString('0'));

        $this->projects()->add($project);

        assertEquals($project, $this->projects()->withName('Project One'));
    }

    /** @test */
    public function on_withName_it_throws_if_there_is_no_project_projection_with_the_given_name()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function on_updateTotalTimeFor_it_updates_the_total_time_for_the_given_project()
    {
        $projectId = ProjectId::generate();
        $project   = new ProjectProjection($projectId, 'Project One', Period::fromString('0'));

        $this->projects()->add($project);

        $this->projects()->updateTotalTimeFor($projectId, Period::fromString('8'));

        assertEquals(Period::fromString('8'), $this->projects()->withId($projectId)->totalTime());
    }

    /** @test */
    public function on_updateTotalTimeFor_it_throws_if_there_is_no_project_projection_for_the_given_id()
    {
        $this->markTestIncomplete();
    }

    private function sortProjects(array $projects) : array
    {
        usort(
            $projects,
            function (ProjectProjection $a, ProjectProjection $b) {
                return $a->name() <=> $b->name();
            }
        );

        return $projects;
    }
}
