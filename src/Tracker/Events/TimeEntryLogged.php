<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Tracker\Date;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\Period;
use TomPHP\TimeTracker\Tracker\ProjectId;

final class TimeEntryLogged extends Event
{
    /** @var ProjectId */
    private $projectId;

    /** @var DeveloperId */
    private $developerId;

    /** @var Date */
    private $date;

    /** @var $period */
    private $period;

    /** @var string */
    private $description;

    public function __construct(
        DeveloperId $developerId,
        ProjectId $projectId,
        Date $date,
        Period $period,
        string $description
    ) {
        $this->projectId        = $projectId;
        $this->developerId      = $developerId;
        $this->date             = $date;
        $this->period           = $period;
        $this->description      = $description;
    }

    public function projectId() : ProjectId
    {
        return $this->projectId;
    }

    public function developerId() : DeveloperId
    {
        return $this->developerId;
    }

    public function date() : Date
    {
        return $this->date;
    }

    public function period() : Period
    {
        return $this->period;
    }

    public function description() : string
    {
        return $this->description;
    }
}
