<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack;

use test\support\TestUsers\Fran;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Slack\Developer;

final class DeveloperTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_values()
    {
        $developer = new Developer(Fran::id(), Fran::name(), Fran::slackHandle());

        assertEquals(Fran::id(), $developer->id());
        assertEquals(Fran::name(), $developer->name());
        assertEquals(Fran::slackHandle(), $developer->slackHandle());
    }
}
