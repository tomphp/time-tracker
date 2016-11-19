<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Common\DeveloperId;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;

final class MemoryDeveloperProjections implements DeveloperProjections
{
    /** @var DeveloperProjection[] */
    private $developersByEmail = [];

    /** @var DeveloperProjection[] */
    private $developersById = [];

    public function add(DeveloperProjection $developer)
    {
        $this->developersById[(string) $developer->id()]       = $developer;
        $this->developersByEmail[(string) $developer->email()] = $developer;
    }

    public function all() : array
    {
        return array_values($this->developersById);
    }

    public function withId(DeveloperId $id) : DeveloperProjection
    {
        return $this->developersById[(string) $id];
    }

    public function withEmail(Email $email) : DeveloperProjection
    {
        return $this->developersByEmail[(string) $email];
    }
}
