<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\Events\TimeEntryLogged;

final class TimeEntry
{
    public static function log(
        TimeEntryId $id,
        DeveloperId $developerId,
        ProjectId $projectId,
        Date $date,
        Period $period,
        string $description
    ) : self {
        EventBus::publish(new TimeEntryLogged(
            $id,
            $developerId,
            $projectId,
            $date,
            $period,
            $description
        ));

        return new self();
    }

    private function __construct()
    {
    }
}
