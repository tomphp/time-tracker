<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Tracker\Period;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjection;

final class ProjectProjectionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_properties()
    {
        $projectId   = ProjectId::generate();
        $name        = 'Example Project';
        $totalTime   = Period::fromString('0');

        $project = new ProjectProjection($projectId, $name, $totalTime);

        assertSame($projectId, $project->projectId());
        assertSame($name, $project->name());
        assertSame($totalTime, $project->totalTime());
    }
}
