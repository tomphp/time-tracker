<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Tracker\Events\ProjectCreated;
use TomPHP\TimeTracker\Tracker\ProjectId;

final class ProjectCreatedTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_properties()
    {
        $projectId   = ProjectId::generate();
        $projectName = 'Example Project';

        $event = new ProjectCreated($projectId, $projectName);

        assertSame($projectId, $event->projectId());
        assertSame($projectName, $event->projectName());
    }
}
