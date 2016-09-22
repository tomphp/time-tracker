<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Tracker\Date;
use TomPHP\TimeTracker\Tracker\Period;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\UserId;

final class TimeEntryProjectionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_properties()
    {
        $userId      = UserId::generate();
        $projectId   = ProjectId::generate();
        $date        = Date::fromString('2016-09-21');
        $period      = Period::fromString('1:30');
        $description = 'Example description';

        $timeEntry = new TimeEntryProjection(
            $userId,
            $projectId,
            $date,
            $period,
            $description
        );

        assertSame($userId, $timeEntry->userId());
        assertSame($projectId, $timeEntry->projectId());
        assertSame($date, $timeEntry->date());
        assertSame($period, $timeEntry->period());
        assertSame($description, $timeEntry->description());
    }
}
