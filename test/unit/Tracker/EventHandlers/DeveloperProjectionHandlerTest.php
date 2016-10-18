<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\EventHandlers;

use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\EventHandlers\DeveloperProjectionHandler;
use TomPHP\TimeTracker\Tracker\Events\DeveloperCreated;

final class DeveloperProjectionHandlerTest extends AbstractEventHandlerTest
{
    /** @var DeveloperProjections */
    private $developers;

    protected function setUp()
    {
        $this->developers = $this->prophesize(DeveloperProjections::class);
    }

    protected function subject()
    {
        return new DeveloperProjectionHandler($this->developers->reveal());
    }

    /** @test */
    public function on_handle_DeveloperCreated_it_stores_a_new_DeveloperProjection()
    {
        $id = DeveloperId::generate();
        $this->subject()->handle(new DeveloperCreated(
            $id,
            'Tom',
            Email::fromString('tom@example.com')
        ));

        $this->developers
            ->add(new DeveloperProjection(
                $id,
                'Tom',
                Email::fromString('tom@example.com')
            ))->shouldHaveBeenCalled();
    }
}
