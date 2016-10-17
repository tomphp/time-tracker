<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

interface EventStore
{
    /** @return void */
    public function store(Event $event);
}
