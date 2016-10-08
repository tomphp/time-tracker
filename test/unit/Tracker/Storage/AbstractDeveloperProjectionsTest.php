<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;

abstract class AbstractDeveloperProjectionsTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function developers() : DeveloperProjections;

    /** @test */
    public function on_withSlackHandle_it_returns_the_developer_with_that_handle()
    {
        $developerId = DeveloperId::generate();
        $developer   = new DeveloperProjection($developerId, 'Tom', SlackHandle::fromString('tom'));

        $this->developers()->add($developer);

        assertSame($developer, $this->developers()->withSlackHandle(SlackHandle::fromString('tom')));
    }

    /** @test */
    public function on_withSlackHandle_it_throws_if_there_is_no_developer_projection_with_the_given_handle()
    {
        $this->markTestIncomplete();
    }
}
