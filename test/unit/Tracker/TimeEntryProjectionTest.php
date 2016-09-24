<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Tracker\Date;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\Period;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;

final class TimeEntryProjectionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_properties()
    {
        $developerId      = DeveloperId::generate();
        $projectId        = ProjectId::generate();
        $date             = Date::fromString('2016-09-21');
        $period           = Period::fromString('1:30');
        $description      = 'Example description';

        $timeEntry = new TimeEntryProjection(
            $developerId,
            $projectId,
            $date,
            $period,
            $description
        );

        assertSame($developerId, $timeEntry->developerId());
        assertSame($projectId, $timeEntry->projectId());
        assertSame($date, $timeEntry->date());
        assertSame($period, $timeEntry->period());
        assertSame($description, $timeEntry->description());
    }
}
