<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Tracker\AggregateId;
use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\Project;
use TomPHP\TimeTracker\Tracker\ProjectId;

final class ProjectCreated extends Event
{
    /** @var ProjectId */
    private $projectId;

    /** @var string */
    private $projectName;

    public static function fromParams(string $idString, array $params) : Event
    {
        return new self(
            ProjectId::fromString($idString),
            $params['name']
        );
    }

    public function __construct(ProjectId $projectId, string $projectName)
    {
        $this->projectId   = $projectId;
        $this->projectName = $projectName;
    }

    public function aggregateId() : AggregateId
    {
        return $this->projectId;
    }

    public function aggregateName() : string
    {
        return Project::class;
    }

    public function projectId() : ProjectId
    {
        return $this->projectId;
    }

    public function projectName() : string
    {
        return $this->projectName;
    }

    public function params() : array
    {
        return ['name' => $this->projectName];
    }
}
