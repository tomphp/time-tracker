<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\EventHandlers;

use Prophecy\Argument;
use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\EventHandlers\ProjectProjectionHandler;
use TomPHP\TimeTracker\Tracker\Events\ProjectCreated;
use TomPHP\TimeTracker\Tracker\Events\TimeEntryLogged;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjection;
use TomPHP\TimeTracker\Tracker\ProjectProjections;

final class ProjectProjectionHandlerTest extends AbstractEventHandlerTest
{
    /** @var ProjectProjections */
    private $projects;

    protected function setUp()
    {
        $this->projects = $this->prophesize(ProjectProjections::class);
    }

    protected function subject()
    {
        return new ProjectProjectionHandler($this->projects->reveal());
    }

    /** @test */
    public function on_handle_ProjectCreated_it_stores_a_new_ProjectProjection()
    {
        $projectId = ProjectId::generate();
        $this->subject()->handle(new ProjectCreated($projectId, 'Example Project'));

        $this->projects
            ->add(new ProjectProjection($projectId, 'Example Project', Period::fromString('0')))
            ->shouldHaveBeenCalled();
    }

    /** @test */
    public function on_handle_TimeEventLogged_it_fetches_the_ProjectProjection()
    {
        $projectId = ProjectId::generate();

        $this->projects
            ->withId(Argument::any())
            ->willReturn(new ProjectProjection($projectId, 'name', Period::fromString('0')));
        $this->projects
            ->updateTotalTimeFor(Argument::any(), Argument::any())
            ->willReturn();

        $this->subject()->handle(new TimeEntryLogged(
            DeveloperId::generate(),
            $projectId,
            Date::fromString('2016-09-20'),
            Period::fromString('1'),
            'Work was done'
        ));

        $this->projects->withId($projectId)->shouldHaveBeenCalled();
    }

    /** @test */
    public function on_handle_TimeEventLogged_it_updates_the_project_with_time_added_to_total_time()
    {
        $projectId = ProjectId::generate();

        $this->projects
            ->withId(Argument::any())
            ->willReturn(new ProjectProjection($projectId, 'name', Period::fromString('5')));
        $this->projects
            ->updateTotalTimeFor(Argument::any(), Argument::any())
            ->willReturn();

        $this->subject()->handle(new TimeEntryLogged(
            DeveloperId::generate(),
            $projectId,
            Date::fromString('2016-09-20'),
            Period::fromString('1'),
            'Work was done'
        ));

        $this->projects
            ->updateTotalTimeFor($projectId, Period::fromString('6'))
            ->shouldHaveBeenCalled();
    }
}
