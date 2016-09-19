<?php

namespace test\unit\TomPHP\TimeTracker\Domain\EventHandlers;

use TomPHP\TimeTracker\Domain\ProjectProjections;
use TomPHP\TimeTracker\Domain\EventHandlers\ProjectProjectionHandler;
use TomPHP\TimeTracker\Domain\ProjectId;
use TomPHP\TimeTracker\Domain\Events\ProjectCreated;
use TomPHP\TimeTracker\Domain\ProjectProjection;

final class ProjectProjectionHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_handle_ProjectCreated_it_stores_a_new_ProjectProjection()
    {
        $projects = $this->prophesize(ProjectProjections::class);
        $handler = new ProjectProjectionHandler($projects->reveal());

        $projectId = ProjectId::generate();
        $handler->handle(new ProjectCreated($projectId, 'Example Project'));

        $projects->add(new ProjectProjection($projectId, 'Example Project'))
            ->shouldHaveBeenCalled();
    }
}
