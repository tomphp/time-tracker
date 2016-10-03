<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\EventHandlers;

use Prophecy\Prophecy\ObjectProphecy;
use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\EventHandlers\TimeEntryProjectionHandler;
use TomPHP\TimeTracker\Tracker\Events\TimeEntryLogged;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

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
        $developerId      = DeveloperId::generate();
        $projectId        = ProjectId::generate();
        $date             = Date::fromString('2016-09-21');
        $period           = Period::fromString('1:30');
        $description      = 'Example description';

        $this->subject()->handle(new TimeEntryLogged(
            $developerId,
            $projectId,
            $date,
            $period,
            $description
        ));

        $this->timeEntries
            ->add(new TimeEntryProjection(
                $developerId,
                $projectId,
                $date,
                $period,
                $description
            ))->shouldHaveBeenCalled();
    }
}
