<?php declare(strict_types=1);

namespace test\support\TestUsers;

use TomPHP\TimeTracker\Common\ProjectId;
use TomPHP\TimeTracker\Slack;

abstract class TestProject
{
    final private function __construct()
    {
    }

    final public static function id() : ProjectId
    {
        return ProjectId::fromString(static::ID);
    }

    final public static function name() : string
    {
        return static::NAME;
    }

    final public static function asSlackProject() : Slack\Project
    {
        return new Slack\Project(static::id(), static::name());
    }
}
