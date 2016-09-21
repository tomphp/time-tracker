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
        $name = 'Example Project';
        $totalTime   = Period::fromString('0');

        $project = new ProjectProjection($projectId, $name, $totalTime);

        assertSame($projectId, $project->projectId());
        assertSame($name, $project->name());
        assertEquals($totalTime, $project->totalTime());
    }
}
