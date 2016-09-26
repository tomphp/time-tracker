<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker;

abstract class AbstractEntityIdTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function className() : string;

    /** @test */
    public function on_toString_it_returns_a_string_representation()
    {
        $string = 'dae81576-11c9-4a99-96da-0d1901c337d0';
        $id     = $this->fromString($string);

        assertSame($string, (string) $id);
    }

    /** @test */
    public function on_generate_it_creates_a_unique_id()
    {
        $id1 = $this->generate();
        $id2 = $this->generate();

        assertNotEquals($id1, $id2);
    }

    protected function generate()
    {
        return call_user_func([$this->className(), 'generate']);
    }

    protected function fromString(string $string)
    {
        return call_user_func([$this->className(), 'fromString'], $string);
    }
}
