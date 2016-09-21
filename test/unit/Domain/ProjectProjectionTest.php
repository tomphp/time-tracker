<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Domain;

use TomPHP\TimeTracker\Domain\Period;
use TomPHP\TimeTracker\Domain\ProjectId;
use TomPHP\TimeTracker\Domain\ProjectProjection;

final class ProjectProjectionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_properties()
    {
        $projectId   = ProjectId::generate();
        $projectName = 'Example Project';
        $totalTime   = Period::fromString('0');

        $project = new ProjectProjection($projectId, $projectName, $totalTime);

        assertSame($projectId, $project->projectId());
        assertSame($projectName, $project->projectName());
        assertEquals($totalTime, $project->totalTime());
    }
}
