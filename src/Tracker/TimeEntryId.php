<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\TimeEntryId as CommonTimeEntryId;

final class TimeEntryId extends CommonTimeEntryId implements AggregateId
{
    use IdGenerator;
}
