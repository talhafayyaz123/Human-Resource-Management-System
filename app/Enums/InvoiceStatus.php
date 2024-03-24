<?php

namespace App\Enums;

class InvoiceStatus
{

    const DRAFT = 'draft';
    const APPROVED = 'approved';
    const SENT = 'sent';
    const PAID = 'paid';
    const WARNING_LEVEL_1 = 'warning level 1';
    const WARNING_LEVEL_2 = 'warning level 2';
    const WARNING_LEVEL_3 = 'warning level 3';

    const ALL = [self::DRAFT, self::APPROVED, self::SENT, self::WARNING_LEVEL_1, self::WARNING_LEVEL_2, self::WARNING_LEVEL_3, self::PAID];

    const WARNING_LEVEL_STATUS = [self::WARNING_LEVEL_1, self::WARNING_LEVEL_2, self::WARNING_LEVEL_3];
}
