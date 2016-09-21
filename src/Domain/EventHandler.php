<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Domain;

abstract class EventHandler
{
    /** @return void */
    public function handle(Event $event)
    {
        $parts  = explode('\\', get_class($event));
        $method = 'handle' . array_pop($parts);

        if (!method_exists($this, $method)) {
            return;
        }

        $this->$method($event);
    }
}
