<?php

namespace TomPHP\TimeTracker\Infrastructure;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\SyslogUdpHandler;
use TomPHP\ContextLogger;

final class LoggerFactory
{
    public function __invoke($name, $papertailHost, $papertrailPort, $correlationId)
    {
        $output = '%channel%.%level_name%: %message% %context%';
        $formatter = new LineFormatter($output);

        $monolog = new Logger($name);
        $syslogHandler = new SyslogUdpHandler($papertailHost, $papertrailPort);
        $syslogHandler->setFormatter($formatter);
        $monolog->pushHandler($syslogHandler);

        return new ContextLogger($monolog, ['correlation_id' => $correlationId]);
    }
}
