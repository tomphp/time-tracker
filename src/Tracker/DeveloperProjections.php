<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\DeveloperId as Id;
use TomPHP\TimeTracker\Common\Email;

interface DeveloperProjections
{
    /** @return void */
    public function add(DeveloperProjection $developer);

    /** @return Developer[] */
    public function all() : array;

    public function withId(Id $id) : DeveloperProjection;

    public function withEmail(Email $email) : DeveloperProjection;
}
