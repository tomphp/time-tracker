<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

final class EventBus
{
    /** @var EventHandler[] */
    private static $handlers = [];

    /** @return void */
    public static function addHandler(EventHandler $handler)
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
    public static function clearHandlers()
    {
        self::$handlers = [];
    }
}
