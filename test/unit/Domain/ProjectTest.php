<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Domain;

use TomPHP\TimeTracker\Domain\EventBus;
use TomPHP\TimeTracker\Domain\EventHandler;
use TomPHP\TimeTracker\Domain\Events\ProjectCreated;
use TomPHP\TimeTracker\Domain\Project;
use TomPHP\TimeTracker\Domain\ProjectId;

final class ProjectTest extends \PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        EventBus::clearHandlers();
    }

    /** @test */
    public function on_create_it_publishes_a_project_created_event()
    {
        $handler = $this->prophesize(EventHandler::class);
        EventBus::addHandler($handler->reveal());

        $projectId = ProjectId::generate();

        Project::create($projectId, 'Example Project');

        $handler->handle(new ProjectCreated($projectId, 'Example Project'))
                ->shouldHaveBeenCalled();
    }
}
