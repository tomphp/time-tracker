<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Domain;

use TomPHP\TimeTracker\Domain\Date;
use TomPHP\TimeTracker\Domain\Period;
use TomPHP\TimeTracker\Domain\ProjectId;
use TomPHP\TimeTracker\Domain\TimeEntryProjection;
use TomPHP\TimeTracker\Domain\UserId;

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
