<?php

namespace App\Helpers;

use App\Constants;

class OrderHelper
{
    public static function order($upper_order = null, $lower_order = null)
    {
        if (isset($upper_order) && isset($lower_order)) {
            $order = ($upper_order + $lower_order) / 2;
        } elseif (isset($lower_order)) {
            $order = (Constants::STARTING_VALUE + $lower_order) / 2;
        } elseif (isset($upper_order)) {
            $order = (Constants::ENDING_VALUE + $upper_order) / 2;
        } else {
            $order = (Constants::STARTING_VALUE + Constants::ENDING_VALUE) / 2;
        }
        return $order;
    }
}
