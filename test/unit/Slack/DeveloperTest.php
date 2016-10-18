<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack;

use test\support\TestUsers\Fran;
use TomPHP\TimeTracker\Slack\Developer;

final class DeveloperTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_values()
    {
        $developer = new Developer(Fran::id(), Fran::name());

        assertEquals(Fran::id(), $developer->id());
        assertEquals(Fran::name(), $developer->name());
    }
}
