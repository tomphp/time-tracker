<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Tracker\AggregateId;
use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\Events\ProjectCreated;
use TomPHP\TimeTracker\Tracker\Project;
use TomPHP\TimeTracker\Tracker\ProjectId;

final class ProjectCreatedTest extends AbstractEventTest
{
    const PROJECT_ID   = 'some-project-id';
    const PROJECT_NAME = 'The Great Project';

    protected function event() : Event
    {
        return new ProjectCreated($this->aggregateId(), self::PROJECT_NAME);
    }

    protected function aggregateId() : AggregateId
    {
        return ProjectId::fromString(self::PROJECT_ID);
    }

    protected function aggregateName() : string
    {
        return Project::class;
    }

    /** @test */
    public function it_exposes_its_properties()
    {
        assertEquals(ProjectId::fromString(self::PROJECT_ID), $this->event()->projectId());
        assertSame(self::PROJECT_NAME, $this->event()->projectName());
    }
}
