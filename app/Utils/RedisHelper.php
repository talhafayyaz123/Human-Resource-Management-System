<?php

namespace App\Utils;

use App\Utils\Logger;

class RedisHelper
{
    private static $redisObj = [];

    public static function getRedis($server, $port, $pw = "")
    {
        try {
            if (isset(self::$redisObj[$server . ":" . $port]) == false) {
                self::$redisObj[$server . ":" . $port] = new \Redis();
                self::$redisObj[$server . ":" . $port]->connect($server, $port);

                if (isset($pw) && $pw != "") {
                    self::$redisObj[$server . ":" . $port]->auth($pw);
                }
            }
        } catch (\Throwable $e) {
            Logger::logException($e);
        }

        return self::$redisObj[$server . ":" . $port];
    }
}
