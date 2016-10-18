<?php declare(strict_types=1);

namespace test\support\TestUsers;

use TomPHP\TimeTracker\Common\DeveloperId;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Slack\SlackUserId;

abstract class TestUser
{
    final private function __construct()
    {
    }

    final public static function id() : DeveloperId
    {
        return DeveloperId::fromString(static::ID);
    }

    final public static function name() : string
    {
        return static::NAME;
    }

    final public static function email() : Email
    {
        return Email::fromString(static::EMAIL);
    }

    final public static function slackUserId() : SlackUserId
    {
        return SlackUserId::fromString(static::SLACK_USER_ID);
    }
}
