<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\Storage\MemoryDeveloperProjections;

final class MemoryDeveloperProjectionsTest extends AbstractDeveloperProjectionsTest
{
    /** DeveloperProjections */
    protected $developers;

    protected function setUp()
    {
        $this->developers = new MemoryDeveloperProjections();
    }

    protected function developers() : DeveloperProjections
    {
        return $this->developers;
    }
}
