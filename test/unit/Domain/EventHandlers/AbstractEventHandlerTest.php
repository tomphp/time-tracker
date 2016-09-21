<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Domain\EventHandlers;

use TomPHP\TimeTracker\Domain\Event;

abstract class AbstractEventHandlerTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function subject();

    /** @test */
    public function on_handle_it_ignores_unknown_events()
    {
        $event = $this->prophesize(Event::class)->reveal();

        $this->subject()->handle($event);

        assertTrue(true); // Just test that no errors are generated
    }
}
