<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use test\unit\TomPHP\TimeTracker\Common\DeveloperIdTest as CommonDeveloperIdTest;
use TomPHP\TimeTracker\Tracker\DeveloperId;

final class DeveloperIdTest extends CommonDeveloperIdTest
{
    use AggregateIdTest;

    protected function className() : string
    {
        return DeveloperId::class;
    }

    /** @test */
    public function it_extends_the_common_developer_id()
    {
        assertInstanceOf(
            \TomPHP\TimeTracker\Common\DeveloperId::class,
            $this->fromString('example-id')
        );
    }
}
