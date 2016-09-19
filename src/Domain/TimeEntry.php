<?php

namespace TomPHP\TimeTracker\Domain;

final class TimeEntry
{
    public static function log(
        UserId $userId,
        ProjectId $projectId,
        Date $data,
        Period $period,
        string $description
    ) : self {
        return new self();
    }
}
