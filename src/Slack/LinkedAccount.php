<?php

namespace TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Common\DeveloperId;

final class LinkedAccount
{
    /** @var DeveloperId */
    private $developerId;

    /** @var SlackUserId */
    private $slackUserId;

    public function __construct(DeveloperId $developerId, SlackUserId $slackUserId)
    {
        $this->developerId = $developerId;
        $this->slackUserId = $slackUserId;
    }

    public function developerId() : DeveloperId
    {
        return $this->developerId;
    }

    public function slackUserId() : SlackUserId
    {
        return $this->slackUserId;
    }
}
