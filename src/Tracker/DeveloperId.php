<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\DeveloperId as CommonDeveloperId;

final class DeveloperId extends CommonDeveloperId implements AggregateId
{
    use IdGenerator;
}
