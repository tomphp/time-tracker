<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

final class TimeEntryProjection
{
    /** @var ProjectId */
    private $projectId;

    /** @var UserId */
    private $userId;

    /** @var Date */
    private $date;

    /** @var $period */
    private $period;

    /** @var string */
    private $description;

    public function __construct(
        UserId $userId,
        ProjectId $projectId,
        Date $date,
        Period $period,
        string $description
    ) {
        $this->projectId   = $projectId;
        $this->userId      = $userId;
        $this->date        = $date;
        $this->period      = $period;
        $this->description = $description;
    }

    public function projectId() : ProjectId
    {
        return $this->projectId;
    }

    public function userId() : UserId
    {
        return $this->userId;
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
