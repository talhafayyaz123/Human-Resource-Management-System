<?php

namespace App\Enums;

class InvoiceCategory
{

    const LICENSE = 'license';
    const MAINTENANCE = 'maintenance';
    const SERVICE = 'service';
    const AMS = 'ams';
    const HOSTING = 'hosting';


    const ALL = [self::LICENSE, self::MAINTENANCE, self::SERVICE, self::AMS, self::HOSTING];
}
