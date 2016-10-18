<?php declare(strict_types=1);

namespace test\support;

use TomPHP\TimeTracker\Tracker\AggregateId;
use TomPHP\TimeTracker\Tracker\Event;

final class MockEvent extends Event
{
    /** @var AggregateId */
    private $id;

    /** @var string */
    private $param;

    /** @return self */
    public static function fromParams(
        string $aggregateId,
        array $params
    ) : Event {
        return new self(
            MockAggregateId::fromString($aggregateId),
            $params['param']
        );
    }

    public function __construct(MockAggregateId $id, string $param)
    {
        $this->id    = $id;
        $this->param = $param;
    }

    public function aggregateId() : AggregateId
    {
        return $this->id;
    }

    public function aggregateName() : string
    {
        return 'mock-aggregate';
    }

    public function params() : array
    {
        return ['param' => $this->param];
    }
}
