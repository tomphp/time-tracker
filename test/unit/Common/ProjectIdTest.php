<?php

namespace test\unit\TomPHP\TimeTracker\Common;

use TomPHP\TimeTracker\Common\ProjectId;

final class ProjectIdTest extends \PHPUnit_Framework_TestCase
{
    protected function className() : string
    {
        return ProjectId::class;
    }

    /** @test */
    public function it_can_be_converted_from_and_to_a_string()
    {
        $string = 'dae81576-11c9-4a99-96da-0d1901c337d0';
        $id     = $this->fromString($string);

        assertSame($string, (string) $id);
    }

    protected function fromString(string $string)
    {
        return call_user_func([$this->className(), 'fromString'], $string);
    }
}
