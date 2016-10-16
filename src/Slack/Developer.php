<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Common\DeveloperId;
use TomPHP\TimeTracker\Common\SlackHandle;

final class Developer
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
