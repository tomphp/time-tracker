<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\SlackHandle;

interface DeveloperProjections
{
    /** @return void */
    public function add(DeveloperProjection $developer);

    public function withId(DeveloperId $id) : DeveloperProjection;

    public function withSlackHandle(SlackHandle $handle) : DeveloperProjection;
}
