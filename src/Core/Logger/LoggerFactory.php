<?php

namespace JoeJuiceBank\Core\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class LoggerFactory
{
    public static function create(): Logger
    {
        $logger = new Logger('joejuicebank');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../../../logs/app.log', Level::Info));

        return $logger;
    }
}