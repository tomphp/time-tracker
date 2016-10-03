<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

interface DeveloperProjections
{
    /** @return void */
    public function add(DeveloperProjection $developer);

    public function withSlackHandle(string $handle) : DeveloperProjection;
}
