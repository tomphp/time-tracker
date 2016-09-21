<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Domain;

final class ProjectProjection
{
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

    public function projectId() : ProjectId
    {
        return $this->projectId;
    }

    public function projectName() : string
    {
        return $this->projectName;
    }

    public function totalTime() : Period
    {
        return $this->totalTime;
    }
}
