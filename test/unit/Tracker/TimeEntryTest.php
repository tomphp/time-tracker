<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\Events\TimeEntryLogged;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntry;
use TomPHP\TimeTracker\Tracker\TimeEntryId;

final class TimeEntryTest extends AbstractAggregateTest
{
    /** @test */
    public function on_log_it_publishes_a_time_entry_logged_event()
    {
        $id               = TimeEntryId::generate();
        $developerId      = DeveloperId::generate();
        $projectId        = ProjectId::generate();
        $date             = Date::fromString('2016-09-20');
        $period           = Period::fromString('5');
        $description      = 'Did some work';

        TimeEntry::log($id, $developerId, $projectId, $date, $period, $description);

        $this->handler()
            ->handle(new TimeEntryLogged(
                $id,
                $developerId,
                $projectId,
                $date,
                $period,
                $description
            ))->shouldHaveBeenCalled();
    }
}
