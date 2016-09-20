<?php

namespace TomPHP\TimeTracker\Domain;

final class EventBus
{
    /** @var EventHandler[] */
    private static $handlers = [];

    /** @return void */
    public static function subscribe(EventHandler $handler)
    {
        self::$handlers[] = $handler;
    }

    /** @return void */
    public static function publish(Event $event)
    {
        foreach (self::$handlers as $handler) {
            $handler->handle($event);
        }
    }

    /** @return void */
    public static function clearSubscribers()
    {
        self::$handlers = [];
    }
}
