<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\Developer;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\Event;

final class DeveloperCreated extends Event
{
    /** @var DeveloperId */
    private $id;

    /** @var string */
    private $name;

    /** @var SlackHandle */
    private $slackHandle;

    public function __construct(DeveloperId $id, string $name, SlackHandle $slackHandle)
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->slackHandle = $slackHandle;
    }

    public function aggregateName() : string
    {
        return Developer::class;
    }

    public function id() : DeveloperId
    {
        return $this->id;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function slackHandle() : SlackHandle
    {
        return $this->slackHandle;
    }
}
