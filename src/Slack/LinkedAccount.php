<?php

namespace TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Common\SlackHandle;

final class LinkedAccount
{
    /** @var string */
    private $developerId;

    /** @var SlackHandle */
    private $slackHandle;

    public function __construct(string $developerId, SlackHandle $slackHandle)
    {
        $this->developerId = $developerId;
        $this->slackHandle = $slackHandle;
    }

    public function developerId() : string
    {
        return $this->developerId;
    }

    public function slackHandle() : SlackHandle
    {
        return $this->slackHandle;
    }
}
