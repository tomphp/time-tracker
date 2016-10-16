<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\ProjectId as CommonProjectId;

final class ProjectId extends CommonProjectId implements AggregateId
{
    use IdGenerator;
}
