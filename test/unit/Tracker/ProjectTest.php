<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Tracker\Events\ProjectCreated;
use TomPHP\TimeTracker\Tracker\Project;
use TomPHP\TimeTracker\Tracker\ProjectId;

final class ProjectTest extends AbstractAggregateTest
{
    /** @test */
    public function on_create_it_publishes_a_project_created_event()
    {
        $projectId = ProjectId::generate();

        Project::create($projectId, 'Example Project');

        $this->handler()
            ->handle(new ProjectCreated($projectId, 'Example Project'))
            ->shouldHaveBeenCalled();
    }
}
