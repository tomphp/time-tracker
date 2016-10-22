<?php declare(strict_types=1);

namespace TomPHP\TimeTracker;

use Interop\Container\ContainerInterface;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\TimeTracker\Tracker\EventBus;

final class Bootstrap
{
    public static function run(ContainerInterface $container)
    {
        error_log('Using driver ' . self::storageDriver());
        Configurator::apply()
            ->configFromFiles(self::projectRoot() . '/config/*.global.php')
            ->configFromFiles(self::projectRoot() . '/config/*.' . self::storageDriver() . '.php')
            ->withSetting(Configurator::SETTING_DEFAULT_SINGLETON_SERVICES, true)
            ->to($container);

        EventBus::clearHandlers();

        foreach ($container->get('config.tracker.event_handlers') as $name) {
            EventBus::addHandler($container->get($name));
        }
    }

    public static function projectRoot()
    {
        return __DIR__ . '/..';
    }

    private static function storageDriver() : string
    {
        return getenv('STORAGE_DRIVER') ?: 'memory';
    }
}
