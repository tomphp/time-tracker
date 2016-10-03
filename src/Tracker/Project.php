<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Tracker\Events\ProjectCreated;

final class Project
{
    public static function create(ProjectId $id, string $name) : self
    {
        EventBus::publish(new ProjectCreated($id, $name));
        return new self();
    }

    private function __construct()
    {
    }
}
