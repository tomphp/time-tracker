<?php declare(strict_types=1);

namespace test\support;

use TomPHP\TimeTracker\Tracker\AggregateId;
use TomPHP\TimeTracker\Tracker\AggregateIdTrait;

final class MockAggregateId implements AggregateId
{
    use AggregateIdTrait;
}
