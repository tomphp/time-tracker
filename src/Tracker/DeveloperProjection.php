<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

final class DeveloperProjection
{
    /** @var DeveloperId */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $slackHandle;

    public function __construct(DeveloperId $id, string $name, string $slackHandle)
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->slackHandle = $slackHandle;
    }

    public function id() : DeveloperId
    {
        return $this->id;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function slackHandle() : string
    {
        return $this->slackHandle;
    }
}
