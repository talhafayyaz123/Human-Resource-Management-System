<?php

namespace App\Utils;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class Logger
{
    private static $redisObj = null;
    private static $serviceName = "unknown";
    private static $globalContext = [];
    private static $correlationId = "";
    private static $loggingConfig = [];
    private static $stdout = null;

    public static function log($message, $type, $properties = [], $traceNum = 1)
    {
        if(self::$stdout === null)
        {
            self::$stdout = fopen('php://stdout', 'wb');
        }


        if ($message != "") {
            $properties["msg"] = $message;
        }

        fwrite(self::$stdout, json_encode(["properties"=>$properties]));

        try
        {
            if (self::$redisObj == null)
            {
                self::$redisObj = new \Redis();
                self::$loggingConfig = config('services.redis');
                self::$redisObj->connect(self::$loggingConfig['logging_ip'], self::$loggingConfig['logging_port']);
                self::$redisObj->auth('dh_redis_pw');
                self::$serviceName = self::$loggingConfig['logging_service_name'];
                self::$correlationId = Str::uuid()->toString();
            }
        }
        catch(\Throwable $e)
        {
            fwrite(self::$stdout, json_encode(["msg"=>"Logger-Connect-Exception: ".$e->getMessage()]));
        }

        $e = new \Exception;
        $trace = $e->getTrace();
        $properties["function"] = $trace[$traceNum]["function"];
        //$properties["line"]=$trace[$traceNum]["line"];
        //$properties["file"]=$trace[$traceNum]["file"];
        $properties["correlation_id"] = self::$correlationId;
        //$properties["fingerprint_ip"]=md5($_SERVER['REMOTE_ADDR']."-".$_SERVER['HTTP_X_FORWARDED_FOR']);

        $msg = [
            "msg" => array_merge(self::$globalContext, $properties),
            "stream" => [
                "type" => $type,
                "service" => self::$serviceName
            ],
        ];

        try
        {
            self::$redisObj->rpush("log_queue", json_encode($msg));
        }
        catch(\Throwable $e)
        {
            fwrite(self::$stdout, json_encode(["msg"=>"Logger-Push-Exception: ".$e->getMessage()]));
        }
    }

    public static function auditLog($properties , $traceNum = 1)
    {
        if(self::$stdout === null)
        {
            self::$stdout = fopen('php://stdout', 'wb');
        }

        fwrite(self::$stdout, json_encode(["properties"=>$properties]));

        try
        {
            if (self::$redisObj == null)
            {
                self::$redisObj = new \Redis();
                self::$loggingConfig = config('services.redis');
                self::$redisObj->connect(self::$loggingConfig['logging_ip'], self::$loggingConfig['logging_port']);
                self::$redisObj->auth('dh_redis_pw');
                self::$serviceName = self::$loggingConfig['logging_service_name'];
                self::$correlationId = Str::uuid()->toString();
            }
        }
        catch(\Throwable $e)
        {
            fwrite(self::$stdout, json_encode(["msg"=>"Logger-Connect-Exception: ".$e->getMessage()]));
        }

        $msg = [
            "msg" => array_merge(self::$globalContext, $properties),
            "stream" => [
                "type" => "audit",
                "service" => self::$serviceName
            ],
        ];

        try
        {
            $service = config('services.environment');
            if ($service['app_environment'] != 'local') {
                self::$redisObj->rPush("audit_log_queue", json_encode($msg));
            }else{
                Log::info(json_encode($msg));
            }
        }
        catch(\Throwable $e)
        {
            fwrite(self::$stdout, json_encode(["msg"=>"Logger-Push-Exception: ".$e->getMessage()]));
        }
    }

    public static function logInfo($message, $properties = [], $traceNum = 2)
    {
        Logger::log($message, "info", $properties, $traceNum);
    }
    public static function logWarning($message, $properties = [], $traceNum = 2)
    {
        Logger::log($message, "warning", $properties, $traceNum);
    }
    public static function logError($message, $properties = [], $traceNum = 2)
    {
        Logger::log($message, "error", $properties, $traceNum);
    }

    public static function addContext($key, $value)
    {
        self::$globalContext[$key] = $value;
    }
    public static function removeContext($key)
    {
        unset(self::$globalContext[$key]);
    }

    public static function logException($e, $message = "", $properties = [], $traceNum = 2): void
    {
        $properties["is_exception"] = true;
        $properties["exception"] = $e->getMessage();
        $properties["exception_line"] = $e->getLine();
        $properties["exception_file"] = $e->getFile();
        $properties["exception_code"] = $e->getCode();

        if ($message == "") {
            $message = $e->getMessage();
        }
        $trace = $e->getTrace();
        if (!empty($trace)) {
            $properties["function_name"] = $trace[0]['function'] ?? '';
        }

        Logger::log($message, "error", $properties, $traceNum);
    }
}
