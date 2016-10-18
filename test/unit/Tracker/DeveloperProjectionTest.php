<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use test\support\TestUsers\Fran;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;

final class DeveloperProjectionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_properties()
    {
        $id          = DeveloperId::fromString((string) Fran::id());

        $developer = new DeveloperProjection($id, Fran::name(), Fran::email());

        assertEquals($id, $developer->id());
        assertEquals(Fran::name(), $developer->name());
        assertEquals(Fran::email(), $developer->email());
    }
}
