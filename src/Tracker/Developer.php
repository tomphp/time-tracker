<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Tracker\Events\DeveloperCreated;

final class Developer
{
    public static function create(DeveloperId $id, string $name, string $slackHandle) : self
    {
        EventBus::publish(new DeveloperCreated($id, $name, $slackHandle));
        return new self();
    }

    private function __construct()
    {
    }
}
