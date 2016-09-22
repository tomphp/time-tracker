<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Tracker\EventBus;
use TomPHP\TimeTracker\Tracker\EventHandler;

abstract class AbstractAggregateTest extends \PHPUnit_Framework_TestCase
{
    /** @var EventHandler */
    private $handler;

    protected function setUp()
    {
        $this->handler = $this->prophesize(EventHandler::class);

        EventBus::addHandler($this->handler->reveal());
    }

    protected function tearDown()
    {
        EventBus::clearHandlers();
    }

    protected function handler()
    {
        return $this->handler;
    }
}
