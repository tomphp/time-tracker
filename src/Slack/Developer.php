<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Common\SlackHandle;

final class Developer
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var SlackHandle */
    private $slackHandle;

    public function __construct(string $id, string $name, SlackHandle $slackHandle)
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->slackHandle = $slackHandle;
    }

    public function id() : string
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
