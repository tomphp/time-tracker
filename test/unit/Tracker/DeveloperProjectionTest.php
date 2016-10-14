<?php

namespace test\unit\TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;

final class DeveloperProjectionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_properties()
    {
        $id          = DeveloperId::generate();
        $name        = 'Example Developer';
        $email       = Email::fromString('tom@example.com');
        $slackHandle = SlackHandle::fromString('@tomoram');

        $developer = new DeveloperProjection($id, $name, $email, $slackHandle);

        assertSame($id, $developer->id());
        assertSame($name, $developer->name());
        assertSame($email, $developer->email());
        assertSame($slackHandle, $developer->slackHandle());
    }
}
