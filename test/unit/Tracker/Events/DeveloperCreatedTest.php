<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Tracker\AggregateId;
use TomPHP\TimeTracker\Tracker\Developer;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\Events\DeveloperCreated;

final class DeveloperCreatedTest extends AbstractEventTest
{
    const DEVELOPER_ID           = 'some-developer-id';
    const DEVELOPER_NAME         = 'The Great Project';
    const DEVELOPER_EMAIL        = 'developer@example.com';

    protected function event() : Event
    {
        return new DeveloperCreated(
            $this->aggregateId(),
            self::DEVELOPER_NAME,
            Email::fromString(self::DEVELOPER_EMAIL)
        );
    }

    protected function aggregateId() : AggregateId
    {
        return DeveloperId::fromString(self::DEVELOPER_ID);
    }

    protected function aggregateName() : string
    {
        return Developer::class;
    }

    /** @test */
    public function it_exposes_its_properties()
    {
        assertEquals(DeveloperId::fromString(self::DEVELOPER_ID), $this->event()->id());
        assertSame(self::DEVELOPER_NAME, $this->event()->name());
        assertEquals(Email::fromString(self::DEVELOPER_EMAIL), $this->event()->email());
    }
}
