<?php

namespace test\unit\TomPHP\TimeTracker\Storage;

use TomPHP\TimeTracker\Domain\ProjectProjection;
use TomPHP\TimeTracker\Storage\MemoryProjectProjections;
use TomPHP\TimeTracker\Domain\ProjectId;

final class MemoryProjectProjectionsTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_all_it_returns_all_added_project_projections()
    {
        $projects = new MemoryProjectProjections();

        $project1 = new ProjectProjection(ProjectId::generate(), 'Project One');
        $project2 = new ProjectProjection(ProjectId::generate(), 'Project Two');

        $projects->add($project1);
        $projects->add($project2);

        assertSame([$project1, $project2], $projects->all());
    }
}
