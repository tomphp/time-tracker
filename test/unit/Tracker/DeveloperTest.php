<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\Developer;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\Events\DeveloperCreated;

final class DeveloperTest extends AbstractAggregateTest
{
    /** @test */
    public function on_create_it_publishes_a_developer_created_event()
    {
        $id = DeveloperId::generate();

        Developer::create($id, 'Tom', SlackHandle::fromString('tom'));

        $this->handler()
            ->handle(new DeveloperCreated($id, 'Tom', SlackHandle::fromString('tom')))
            ->shouldHaveBeenCalled();
    }
}
