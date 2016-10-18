<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\Email;

interface DeveloperProjections
{
    /** @return void */
    public function add(DeveloperProjection $developer);

    public function withId(DeveloperId $id) : DeveloperProjection;

    public function withEmail(Email $email) : DeveloperProjection;
}
