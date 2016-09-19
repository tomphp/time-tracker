<?php

namespace TomPHP\TimeTracker\Domain;

use TomPHP\TimeTracker\Util\ReadOnlyProperties;
use TomPHP\TimeTracker\Domain\ProjectId;

/**
 * @property ProjectId $projectId
 * @property string    $projectName
 */
final class ProjectProjection
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
