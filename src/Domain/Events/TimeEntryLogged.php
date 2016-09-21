<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Domain\Events;

use TomPHP\TimeTracker\Domain\Date;
use TomPHP\TimeTracker\Domain\Event;
use TomPHP\TimeTracker\Domain\Period;
use TomPHP\TimeTracker\Domain\ProjectId;
use TomPHP\TimeTracker\Domain\UserId;

final class TimeEntryLogged extends Event
{
    /** @var ProjectId */
    private $projectId;

    /** @var $period */
    private $period;

    public function __construct(
        UserId $userId,
        ProjectId $projectId,
        Date $date,
        Period $period,
        string $description
    ) {
        $this->projectId = $projectId;
        $this->period    = $period;
    }

    public function projectId() : ProjectId
    {
        return $this->projectId;
    }

    public function period() : Period
    {
        return $this->period;
    }
}
