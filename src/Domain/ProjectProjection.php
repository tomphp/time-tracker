<?php

namespace TomPHP\TimeTracker\Domain;

use TomPHP\TimeTracker\Util\ReadOnlyProperties;

/**
 * @property ProjectId $projectId
 * @property string    $projectName
 * @property Period    $totalTime
 */
final class ProjectProjection
{
    use ReadOnlyProperties;

    /** @var ProjectId */
    private $projectId;

    /** @var string */
    private $projectName;

    /** @var Period */
    private $totalTime;

    public function __construct(
        ProjectId $projectId,
        string $projectName,
        Period $totalTime
    ) {
        $this->projectId   = $projectId;
        $this->projectName = $projectName;
        $this->totalTime   = $totalTime;
    }
}
