<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use test\unit\TomPHP\TimeTracker\Common\ProjectIdTest as CommonProjectIdTest;
use TomPHP\TimeTracker\Tracker\ProjectId;

final class ProjectIdTest extends CommonProjectIdTest
{
    use AggregateIdTest;

    protected function className() : string
    {
        return ProjectId::class;
    }

    /** @test */
    public function it_extends_the_common_project_id()
    {
        assertInstanceOf(
            \TomPHP\TimeTracker\Common\ProjectId::class,
            $this->fromString('example-id')
        );
    }
}
