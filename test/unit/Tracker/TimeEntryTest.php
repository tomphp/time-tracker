<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Tracker\Date;
use TomPHP\TimeTracker\Tracker\Events\TimeEntryLogged;
use TomPHP\TimeTracker\Tracker\Period;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntry;
use TomPHP\TimeTracker\Tracker\UserId;

final class TimeEntryTest extends AbstractAggregateTest
{
    /** @test */
    public function on_log_it_publishes_a_time_entry_logged_event()
    {
        $userId      = UserId::generate();
        $projectId   = ProjectId::generate();
        $date        = Date::fromString('2016-09-20');
        $period      = Period::fromString('5');
        $description = 'Did some work';

        TimeEntry::log($userId, $projectId, $date, $period, $description);

        $this->handler()
            ->handle(new TimeEntryLogged(
                $userId,
                $projectId,
                $date,
                $period,
                $description
            ))->shouldHaveBeenCalled();
    }
}
