<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\AggregateId;
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

    public static function fromParams(string $idString, array $params) : Event
    {
    }

    public function __construct(DeveloperId $id, string $name, SlackHandle $slackHandle)
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->slackHandle = $slackHandle;
    }

    public function aggregateId() : AggregateId
    {
        return $this->id;
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

    public function params() : array
    {
        return [];
    }
}
