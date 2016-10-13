<?php

namespace TomPHP\TimeTracker\Tracker;

interface EventStore
{
    /** @return void */
    public function store(Event $event);
}
