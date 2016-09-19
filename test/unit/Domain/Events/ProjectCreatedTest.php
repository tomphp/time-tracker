<?php

namespace test\unit\TomPHP\TimeTracker\Domain\Events;

use TomPHP\TimeTracker\Domain\ProjectId;
use TomPHP\TimeTracker\Domain\Events\ProjectCreated;

final class ProjectCreatedTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_properties()
    {
        $projectId = ProjectId::generate();
        $projectName = 'Example Project';

        $event = new ProjectCreated($projectId, $projectName);

        assertSame($projectId, $event->projectId);
        assertSame($projectName, $event->projectName);
    }
}
