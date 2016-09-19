<?php

namespace TomPHP\TimeTracker\Domain;

interface ProjectProjections
{
    /** @return ProjectProjection */
    public function all() : array;

    /** @return void */
    public function add(ProjectProjection $project);
}
