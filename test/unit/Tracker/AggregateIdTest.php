<?php

namespace test\unit\TomPHP\TimeTracker\Tracker;

trait AggregateIdTest
{
    abstract protected function className() : string;

    /** @test */
    public function on_generate_it_creates_a_unique_id()
    {
        $id1 = $this->generate();
        $id2 = $this->generate();

        assertNotEquals($id1, $id2);
    }

    /** @test */
    public function on_generate_it_creates_an_intance_of_the_tracker_id()
    {
        assertInstanceOf($this->className(), $this->generate());
    }

    protected function generate()
    {
        return call_user_func([$this->className(), 'generate']);
    }
}
