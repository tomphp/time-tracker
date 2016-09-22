<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Domain;

final class ProjectProjection
{
    /** @var ProjectId */
    private $projectId;

    /** @var string */
    private $name;

    /** @var Period */
    private $totalTime;

    public function __construct(
        ProjectId $projectId,
        string $name,
        Period $totalTime
    ) {
        $this->projectId   = $projectId;
        $this->name        = $name;
        $this->totalTime   = $totalTime;
    }

    public function projectId() : ProjectId
    {
        return $this->projectId;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function totalTime() : Period
    {
        return $this->totalTime;
    }
}
