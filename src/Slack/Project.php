<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Common\ProjectId;

final class Project
{
    /** @var ProjectId */
    private $id;

    /** @var string */
    private $name;

    public function __construct(ProjectId $id, string $name)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    public function id() : ProjectId
    {
        return $this->id;
    }

    public function name() : string
    {
        return $this->name;
    }
}
