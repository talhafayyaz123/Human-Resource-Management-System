<?php

namespace App\Helpers;

use CustomerPortal\Utils\Logger;
use Illuminate\Support\Facades\Log;

class ApiTokenHelper
{
    private static $redisObj = null;
    private static $httpClient = null;
    private static $baseUrl = null;
    private static $configHttp = null;
    private static $configRedis = null;

    public static function getApiToken()
    {
        try {
            if (self::$redisObj == null) {
                self::$configRedis = config('apitoken.redis_api_cache');
                self::$configHttp = config('apitoken.auth_service');

                self::$httpClient = new \GuzzleHttp\Client();
                self::$baseUrl = self::$configHttp['base_url'];

                self::$redisObj = new \Redis();
                self::$redisObj->connect(self::$configRedis['ip'], self::$configRedis['port']);

                if (isset(self::$configRedis['pw']) && self::$configRedis['pw'] != "") {
                    self::$redisObj->auth(self::$configRedis['pw']);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error', [
                'exception' => $e
            ]);
        }

        $jwt = "";
        try {
            $jwt = self::$redisObj->get("jwt_api_token");
        } catch (\Throwable $e) {
        }

        if ($jwt == "") {
            $response = self::$httpClient->post(self::$baseUrl . '/get-api-jwt', [
                'json' =>
                [
                    'secret' => self::$configHttp['secret'],
                    "client_id" => self::$configHttp['client_id']
                ]
            ]);

            $resp = $response->getBody()->getContents();

            if ($response->getStatusCode() != 200 && $response->getStatusCode() != 204) {
                throw new \Exception('Not able to retrieve jwt from API: ' . $resp);
            }

            $json = json_decode($resp, true);

            if (isset($json) && isset($json["token"])) {
                $jwt = $json["token"];
                try {
                    self::$redisObj->setEx('jwt_api_token', 59 * 59, $jwt);
                } catch (\Throwable $e) {
                }
            } else {
                throw new \Exception('Not able to retrieve jwt from API: ' . $resp);
            }
        }

        return $jwt;
    }
}
