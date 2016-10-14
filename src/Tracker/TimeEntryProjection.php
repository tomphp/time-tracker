<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;

final class TimeEntryProjection
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
        TimeEntryId $id,
        DeveloperId $userId,
        ProjectId $projectId,
        Date $date,
        Period $period,
        string $description
    ) {
        $this->id               = $id;
        $this->projectId        = $projectId;
        $this->developerId      = $userId;
        $this->date             = $date;
        $this->period           = $period;
        $this->description      = $description;
    }

    public function id() : TimeEntryId
    {
        return $this->id;
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
