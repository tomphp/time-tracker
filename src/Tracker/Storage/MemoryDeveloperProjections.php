<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;

final class MemoryDeveloperProjections implements DeveloperProjections
{
    /** @var DeveloperProjection[] */
    private $developersBySlackHandle = [];

    /** @var DeveloperProjection[] */
    private $developersById = [];

    public function add(DeveloperProjection $developer)
    {
        $this->developersBySlackHandle[(string) $developer->slackHandle()] = $developer;
        $this->developersById[(string) $developer->id()]                   = $developer;
    }

    public function withId(DeveloperId $id) : DeveloperProjection
    {
        return $this->developersById[(string) $id];
    }

    public function withSlackHandle(SlackHandle $handle) : DeveloperProjection
    {
        return $this->developersBySlackHandle[(string) $handle];
    }
}
