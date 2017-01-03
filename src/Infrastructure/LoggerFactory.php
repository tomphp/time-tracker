<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Infrastructure;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Logger;
use TomPHP\ContextLogger;

final class LoggerFactory
{
    public function __invoke($name, $papertailHost, $papertrailPort, $correlationId)
    {
        $output    = '%channel%.%level_name%: %message% %context%';
        $formatter = new LineFormatter($output);

        $monolog       = new Logger($name);
        $syslogHandler = new SyslogUdpHandler($papertailHost, $papertrailPort);
        $syslogHandler->setFormatter($formatter);
        $monolog->pushHandler($syslogHandler);

        return new ContextLogger($monolog, ['correlation_id' => $correlationId]);
    }
}
