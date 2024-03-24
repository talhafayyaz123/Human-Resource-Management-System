<?php

namespace App\Enums;

class InvoiceType
{

    const INVOICE_CORRECTION = 'invoice-correction';
    const INVOICE = 'invoice';
    const INVOICE_STORNO = 'invoice-storno';


    const ALL = [self::INVOICE_CORRECTION, self::INVOICE, self::INVOICE_STORNO];
}
