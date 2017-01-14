<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Infrastructure;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\RollbarHandler;
use Monolog\Logger;
use Rollbar;
use TomPHP\ContextLogger;

final class LoggerFactory
{
    public function __invoke(string $name, string $correlationId) : ContextLogger
    {
        $output    = '%channel%.%level_name%: %message% %context%';
        $formatter = new LineFormatter($output);
        $monolog   = new Logger($name);

        if (Rollbar::$instance) {
            $rollbarHandler = new RollbarHandler(Rollbar::$instance, Logger::WARNING);
            $rollbarHandler->setFormatter($formatter);
            $monolog->pushHandler($rollbarHandler);
        }

        $monolog->pushHandler(new ErrorLogHandler());

        return new ContextLogger($monolog, ['correlation_id' => $correlationId]);
    }
}
