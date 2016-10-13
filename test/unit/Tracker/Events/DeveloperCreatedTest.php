<?php

namespace test\unit\TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\Developer;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\Events\DeveloperCreated;

final class DeveloperCreatedTest extends \PHPUnit_Framework_TestCase
{
    const DEVELOPER_ID           = 'some-project-id';
    const DEVELOPER_NAME         = 'The Great Project';
    const DEVELOPER_SLACK_HANDLE = 'slack-user';

    protected function event() : Event
    {
        return new DeveloperCreated(
            DeveloperId::fromString(self::DEVELOPER_ID),
            self::DEVELOPER_NAME,
            SlackHandle::fromString(self::DEVELOPER_SLACK_HANDLE)
        );
    }

    /** @test */
    public function it_exposes_its_aggregate_type()
    {
        assertSame(Developer::class, $this->event()->aggregateName());
    }
}
