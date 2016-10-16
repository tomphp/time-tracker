<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use test\unit\TomPHP\TimeTracker\Common\TimeEntryIdTest as CommonTimeEntryIdTest;
use TomPHP\TimeTracker\Tracker\TimeEntryId;

final class TimeEntryIdTest extends CommonTimeEntryIdTest
{
    use AggregateIdTest;

    protected function className() : string
    {
        return TimeEntryId::class;
    }

    /** @test */
    public function it_extends_the_common_project_id()
    {
        assertInstanceOf(
            \TomPHP\TimeTracker\Common\TimeEntryId::class,
            $this->fromString('example-id')
        );
    }
}
