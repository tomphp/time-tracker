<?php

namespace TomPHP\TimeTracker\Domain\Events;

use TomPHP\TimeTracker\Domain\Event;
use TomPHP\TimeTracker\Util\ReadOnlyProperties;
use TomPHP\TimeTracker\Domain\ProjectId;

/**
 * @property ProjectId $projectId
 * @property string    $projectName
 */
final class ProjectCreated extends Event
{
    use ReadOnlyProperties;

    /** @var ProjectId */
    private $projectId;

    /** @var string */
    private $projectName;

    public function __construct(ProjectId $projectId, string $projectName)
    {
        $this->projectId   = $projectId;
        $this->projectName = $projectName;
    }
}
