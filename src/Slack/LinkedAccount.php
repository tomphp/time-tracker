<?php

namespace TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Common\DeveloperId;
use TomPHP\TimeTracker\Common\SlackHandle;

final class LinkedAccount
{
    /** @var DeveloperId */
    private $developerId;

    /** @var SlackHandle */
    private $slackHandle;

    public function __construct(DeveloperId $developerId, SlackHandle $slackHandle)
    {
        $this->developerId = $developerId;
        $this->slackHandle = $slackHandle;
    }

    public function developerId() : DeveloperId
    {
        return $this->developerId;
    }

    public function slackHandle() : SlackHandle
    {
        return $this->slackHandle;
    }
}
