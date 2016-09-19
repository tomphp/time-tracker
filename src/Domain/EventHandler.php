<?php

namespace TomPHP\TimeTracker\Domain;

abstract class EventHandler
{
    /**
     * @return void
     */
    public function handle(Event $event)
    {
        $parts = explode('\\', get_class($event));
        $method = 'handle' . array_pop($parts);

        $this->$method($event);
    }
}
