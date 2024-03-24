<?php

namespace App\Traits;

use App\Constants;

trait NestedArrayTraits
{
    public static function getKeysArray($keys): array
    {
        if (is_string($keys)) {
            return empty($keys) ? [] : explode(Constants::KEY_SEPARATOR, $keys);
        }
        return is_null($keys) ? [] : array_filter(array_values(self::forceArray($keys)), function ($value) {
            return $value !== null && $value !== '' && (is_string($value) || is_int($value));
        });
    }

    public static function set($array, $keys, $value)
    {
        return self::setNestedElement($array, $keys, $value);
    }

    public static function setNestedElement($array, $keys, $value)
    {
        $result = $array;
        $keysArray = self::getKeysArray($keys);

        // If no keys specified then preserve array
        if (empty($keysArray)) {
            return $result;
        }

        $tmp = &$result;

        while (count($keysArray) > 0) {
            $key = array_shift($keysArray);
            if (!is_array($tmp)) {
                $tmp = [];
            }
            if ($key === Constants::AUTO_INDEX_KEY) {
                $tmp[] = null;
                end($tmp);
                $key = key($tmp);
            }
            $tmp = &$tmp[$key];
        }
        $tmp[] = $value;

        return $result;
    }
}
