<?php

namespace test\unit\TomPHP\TimeTracker\Domain;

use TomPHP\TimeTracker\Domain\EventBus;
use TomPHP\TimeTracker\Domain\EventHandler;
use TomPHP\TimeTracker\Domain\ProjectId;
use TomPHP\TimeTracker\Domain\Project;
use TomPHP\TimeTracker\Domain\Events\ProjectCreated;

final class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_create_it_publishes_a_project_created_event()
    {
        $handler = $this->prophesize(EventHandler::class);
        EventBus::subscribe($handler->reveal());

        $projectId = ProjectId::generate();

        Project::create($projectId, 'Example Project');

        $handler->handle(new ProjectCreated($projectId, 'Example Project'))
                ->shouldHaveBeenCalled();
    }
}
