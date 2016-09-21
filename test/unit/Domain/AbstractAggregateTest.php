<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Domain;

use TomPHP\TimeTracker\Domain\EventBus;
use TomPHP\TimeTracker\Domain\EventHandler;

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
