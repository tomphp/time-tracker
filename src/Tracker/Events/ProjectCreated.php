<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\ProjectId;

final class ProjectCreated extends Event
{
    /** @var ProjectId */
    private $projectId;

    /** @var string */
    private $projectName;

    public function __construct(ProjectId $projectId, string $projectName)
    {
        $this->projectId   = $projectId;
        $this->projectName = $projectName;
    }

    public function projectId() : ProjectId
    {
        return $this->projectId;
    }

    public function projectName() : string
    {
        return $this->projectName;
    }
}
