<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Domain;

use TomPHP\TimeTracker\Domain\Events\ProjectCreated;
use TomPHP\TimeTracker\Domain\Project;
use TomPHP\TimeTracker\Domain\ProjectId;

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
