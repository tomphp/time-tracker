<?php

namespace test\unit\TomPHP\TimeTracker\Domain;

use TomPHP\TimeTracker\Domain\ProjectId;
use TomPHP\TimeTracker\Domain\ProjectProjection;

final class ProjectProjectionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_properties()
    {
        $projectId = ProjectId::generate();
        $projectName = 'Example Project';

        $project = new ProjectProjection($projectId, $projectName);

        assertSame($projectId, $project->projectId);
        assertSame($projectName, $project->projectName);
    }
}
