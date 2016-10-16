<?php

namespace test\support\TestUsers;

use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Common\SlackHandle;

abstract class TestUser
{
    private function __construct()
    {
    }

    public static function id() : string
    {
        return static::ID;
    }

    public static function name() : string
    {
        return static::NAME;
    }

    public static function email() : Email
    {
        return Email::fromString(static::EMAIL);
    }

    public static function slackHandle() : SlackHandle
    {
        return SlackHandle::fromString(static::SLACK_HANDLE);
    }
}
