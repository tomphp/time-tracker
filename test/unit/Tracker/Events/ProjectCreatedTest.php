<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\Events\ProjectCreated;
use TomPHP\TimeTracker\Tracker\Project;
use TomPHP\TimeTracker\Tracker\ProjectId;

final class ProjectCreatedTest extends \PHPUnit_Framework_TestCase
{
    const PROJECT_ID   = 'some-project-id';
    const PROJECT_NAME = 'The Great Project';

    protected function event() : Event
    {
        return new ProjectCreated(
            ProjectId::fromString(self::PROJECT_ID),
            self::PROJECT_NAME
        );
    }

    /** @test */
    public function it_exposes_its_aggregate_type()
    {
        assertSame(Project::class, $this->event()->aggregateName());
    }

    /** @test */
    public function it_exposes_its_properties()
    {
        assertEquals(ProjectId::fromString(self::PROJECT_ID), $this->event()->projectId());
        assertSame(self::PROJECT_NAME, $this->event()->projectName());
    }
}
