<?php

namespace App\Services\MailService;

use App\Utils\RedisHelper;

class MailService
{
    private static $redisObj = null;
    private static $serviceName = "unknown";
    private static $globalContext = [];
    private static $correlationId = 0;

    private static function prepareFiles(&$filesArray)
    {
        if (isset($filesArray["base64_content"]) || isset($filesArray["content"])) {
            $filesArray = [$filesArray];
        }

        foreach ($filesArray as $key => $arr) {
            if (isset($filesArray[$key]["base64_content"]) == false && isset($filesArray[$key]["content"])) {
                $filesArray[$key]["base64_content"] = base64_encode($filesArray[$key]["content"]);
                unset($filesArray[$key]["content"]);
            }
        }
    }

    private static function getRedisConnection()
    {
        $ip = getenv('REDIS_MAIL_IP');
        $port = getenv('REDIS_MAIL_PORT');

        if ($ip == null || $ip == "") {
            $ip = config('services.mail.redis_ip');
            $port = config('services.mail.redis_port');
        }

        return RedisHelper::getRedis($ip, $port);
    }

    public static function sendMail($from, $mails, $subject, $htmlMessage, $data = [], $filesArray = [])
    {
        self::prepareFiles($filesArray);

        $arr = [
            "data" => $data,
            "subject" => $subject,
            "html" => $htmlMessage,
            "mails" =>  $mails,
            "files" => $filesArray
        ];

        $redisObj = self::getRedisConnection();

        $queue = preg_replace("/[^A-Za-z0-9 ]/", '', $from) . "_mail_queue";
        $ret = $redisObj->rpush($queue, json_encode($arr));

        return $ret;
    }

    public static function sendMailByTemplate($from, $mails, $templateId, $data = [], $filesArray = [])
    {
        self::prepareFiles($filesArray);

        $arr = [
            "data" => $data,
            "id" => $templateId,
            "mails" =>  $mails,
            "files" => $filesArray
        ];

        $redisObj = self::getRedisConnection();

        $queue = preg_replace("/[^A-Za-z0-9 ]/", '', $from) . "_mail_queue";
        $ret = $redisObj->rpush($queue, json_encode($arr));

        return $ret;
    }
}
