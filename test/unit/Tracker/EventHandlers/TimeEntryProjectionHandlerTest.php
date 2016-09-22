<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\EventHandlers;

use Prophecy\Prophecy\ObjectProphecy;
use TomPHP\TimeTracker\Tracker\Date;
use TomPHP\TimeTracker\Tracker\EventHandlers\TimeEntryProjectionHandler;
use TomPHP\TimeTracker\Tracker\Events\TimeEntryLogged;
use TomPHP\TimeTracker\Tracker\Period;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;
use TomPHP\TimeTracker\Tracker\UserId;

final class TimeEntryProjectionHandlerTest extends AbstractEventHandlerTest
{
    /** @var ObjectProphecy */
    private $timeEntries;

    protected function setUp()
    {
        $this->timeEntries = $this->prophesize(TimeEntryProjections::class);
    }

    protected function subject()
    {
        return new TimeEntryProjectionHandler($this->timeEntries->reveal());
    }

    /** @test */
    public function on_handle_TimeEntryLogged_it_stores_a_new_TimeEntryProjection()
    {
        $userId      = UserId::generate();
        $projectId   = ProjectId::generate();
        $date        = Date::fromString('2016-09-21');
        $period      = Period::fromString('1:30');
        $description = 'Example description';

        $this->subject()->handle(new TimeEntryLogged(
            $userId,
            $projectId,
            $date,
            $period,
            $description
        ));

        $this->timeEntries
            ->add(new TimeEntryProjection(
                $userId,
                $projectId,
                $date,
                $period,
                $description
            ))->shouldHaveBeenCalled();
    }
}
