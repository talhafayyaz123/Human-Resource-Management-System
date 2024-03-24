<?php

namespace App\Helpers;

class GenerateUuidHelper
{
    public static $replicaId = -1;
    public static $serviceId = -1;
    private static $counter = 0;

    private static function logical_right_shift($int, $shft)
    {
        return ($int >> $shft)   //Arithmetic right shift
            & (PHP_INT_MAX >> ($shft - 1));   //Deleting unnecessary bits
    }

    public static function generateUUID()
    {
        if (self::$serviceId <= -1) {
            self::$serviceId = getenv("K8S_SERVICE_ID");
            if (self::$serviceId === false) {
                self::$serviceId = rand(300, 511);
            }
        }

        if (self::$replicaId <= -1) {
            self::$replicaId = getenv("K8S_REPLICA_ID");
            if (self::$replicaId === false) {
                self::$replicaId = rand(1000, 2047);
            }
        }

        return self::_generateUUID(self::$serviceId, self::$replicaId);
    }

    private static function _generateUUID($serviceId, $replicaId)
    {
        $uuidPart1 = intval(microtime(true) * 1000) << 20;
        $uuidPart1 |= ($serviceId & 511) << 11;
        $uuidPart1 |= ($replicaId & 2047);

        $uuidPart2 = self::logical_right_shift(mt_rand(0, PHP_INT_MAX), 8);
        self::$counter = (self::$counter + 1) % 255;
        $uuidPart2 |= self::$counter << 56;

        return sprintf(
            '%08x-%04x-%04x-%04x-%012x',
            self::logical_right_shift($uuidPart1 & (0xffffffff << 32), 32),
            self::logical_right_shift($uuidPart1 & (0xffff << 16), 16),
            $uuidPart1 & 0xffff,
            self::logical_right_shift($uuidPart2 & (0xffff << 48), 48),
            $uuidPart2 & (0xffffffffffff)
        );
    }
}
