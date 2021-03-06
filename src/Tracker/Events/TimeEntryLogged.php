<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\AggregateId;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntry;
use TomPHP\TimeTracker\Tracker\TimeEntryId;

final class TimeEntryLogged extends Event
{
    /** @var TimeEntryId */
    private $timeEntryId;

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

    public static function fromParams(string $idString, array $params) : Event
    {
        return new self(
            TimeEntryId::fromString($idString),
            DeveloperId::fromString($params['developer_id']),
            ProjectId::fromString($params['project_id']),
            Date::fromString($params['date']),
            Period::fromString($params['period']),
            $params['description']
        );
    }

    public function __construct(
        TimeEntryId $timeEntryId,
        DeveloperId $developerId,
        ProjectId $projectId,
        Date $date,
        Period $period,
        string $description
    ) {
        $this->timeEntryId      = $timeEntryId;
        $this->projectId        = $projectId;
        $this->developerId      = $developerId;
        $this->date             = $date;
        $this->period           = $period;
        $this->description      = $description;
    }

    public function aggregateId() : AggregateId
    {
        return $this->timeEntryId;
    }

    public function aggregateName() : string
    {
        return TimeEntry::class;
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

    public function params() : array
    {
        return [
            'project_id'   => (string) $this->projectId,
            'developer_id' => (string) $this->developerId,
            'date'         => (string) $this->date,
            'period'       => (string) $this->period,
            'description'  => $this->description,
        ];
    }
}
