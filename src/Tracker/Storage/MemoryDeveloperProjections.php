<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;

final class MemoryDeveloperProjections implements DeveloperProjections
{
    /** @var DeveloperProjection[] */
    private $developersBySlackHandle = [];

    public function add(DeveloperProjection $developer)
    {
        $this->developersBySlackHandle[(string) $developer->slackHandle()] = $developer;
    }

    public function withSlackHandle(SlackHandle $handle) : DeveloperProjection
    {
        return $this->developersBySlackHandle[(string) $handle];
    }
}
