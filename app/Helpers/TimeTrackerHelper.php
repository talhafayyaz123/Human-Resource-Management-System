<?php

namespace App\Helpers;

class TimeTrackerHelper
{

    public static function getTimeTrackedForHumansAttribute($seconds)
    {
        return sprintf('%dh %dm', $seconds / 3600, floor($seconds / 60) % 60);
    }
}
