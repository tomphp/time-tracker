<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\EventHandlers;

use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\EventHandler;
use TomPHP\TimeTracker\Tracker\Events\DeveloperCreated;

final class DeveloperProjectionHandler extends EventHandler
{
    /** @var DeveloperProjections */
    private $developers;

    public function __construct(DeveloperProjections $developers)
    {
        $this->developers = $developers;
    }

    protected function handleDeveloperCreated(DeveloperCreated $event)
    {
        $this->developers->add(new DeveloperProjection(
            $event->id(),
            $event->name(),
            $event->email()
        ));
    }
}
