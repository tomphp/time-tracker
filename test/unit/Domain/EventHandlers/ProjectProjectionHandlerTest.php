<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Domain\EventHandlers;

use Prophecy\Argument;
use TomPHP\TimeTracker\Domain\Date;
use TomPHP\TimeTracker\Domain\Event;
use TomPHP\TimeTracker\Domain\EventHandlers\ProjectProjectionHandler;
use TomPHP\TimeTracker\Domain\Events\ProjectCreated;
use TomPHP\TimeTracker\Domain\Events\TimeEntryLogged;
use TomPHP\TimeTracker\Domain\Period;
use TomPHP\TimeTracker\Domain\ProjectId;
use TomPHP\TimeTracker\Domain\ProjectProjection;
use TomPHP\TimeTracker\Domain\ProjectProjections;
use TomPHP\TimeTracker\Domain\UserId;

final class ProjectProjectionHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ProjectProjections */
    private $projects;

    /** @var ProjectProjectionHandler */
    private $subject;

    protected function setUp()
    {
        $this->projects = $this->prophesize(ProjectProjections::class);
        $this->subject  = new ProjectProjectionHandler($this->projects->reveal());
    }

    /** @test */
    public function on_handle_it_ignores_unknown_events()
    {
        $event = $this->prophesize(Event::class)->reveal();

        $this->subject->handle($event);

        assertTrue(true); // Just test that no errors are generated
    }

    /** @test */
    public function on_handle_ProjectCreated_it_stores_a_new_ProjectProjection()
    {
        $projectId = ProjectId::generate();
        $this->subject->handle(new ProjectCreated($projectId, 'Example Project'));

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

        $this->subject->handle(new TimeEntryLogged(
            UserId::generate(),
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

        $this->subject->handle(new TimeEntryLogged(
            UserId::generate(),
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
