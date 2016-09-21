<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Domain;

use TomPHP\TimeTracker\Domain\Events\TimeEntryLogged;

final class TimeEntry
{
    public static function log(
        UserId $userId,
        ProjectId $projectId,
        Date $date,
        Period $period,
        string $description
    ) : self {
        EventBus::publish(new TimeEntryLogged(
            $userId,
            $projectId,
            $date,
            $period,
            $description
        ));

        return new self();
    }
}