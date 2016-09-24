<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Slack\Project;

final class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_values()
    {
        $developer = new Project('id', 'name');

        assertSame('id', $developer->id());
        assertSame('name', $developer->name());
    }
}
