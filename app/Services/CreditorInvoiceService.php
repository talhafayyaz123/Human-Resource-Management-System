<?php

namespace App\Services;

use App\Models\CreditorInvoice;
use App\Models\Company;
use Carbon\Carbon;
use App\Traits\CustomHelper;

class CreditorInvoiceService
{
    use CustomHelper;
    /**
     * Create a new creditor_invoice.
     *
     * @param  array  $data
     * @return \App\Models\CreditorInvoice
     */
    public function createCreditorInvoice(array $data)
    {
        $company = Company::where('company_name', 'LIKE', '%' . $data['objKeys']['COMPANY_NAME'] . '%')->firstOrFail();
        $creditor_invoice = CreditorInvoice::create([
            'invoice_number' => @$data['objKeys']['ACCOUNTING_NUMBER'],
            'invoice_type' => @$data['objKeys']['invoiceType'],
            'due_date' => Carbon::parse($data['objKeys']['ACCOUNTING_DUE_DATE']),
            'start_date' => Carbon::parse($data['objKeys']['ACCOUNTING_DATEV_FISCALYEAR']),
            'end_date' => Carbon::parse($data['objKeys']['ACCOUNTING_DATEV_POSTINGPROPOSAL_CREATED']),
            'company_id' => $company->id,
            'user_id' => @$data['objKeys']['userId'],
            'invoice_period' => @$data['objKeys']['invoicePeriod'],
            'total_amount' => $this->convertGermanNumberToDecimal($data['objKeys']['ACCOUNTING_TOTAL_AMOUNT']),
            'total_amount_without_tax' => $this->convertGermanNumberToDecimal($data['objKeys']['ACCOUNTING_NET_AMOUNT']),
            'total_tax_amount' => $this->convertGermanNumberToDecimal($data['objKeys']['ACCOUNTING_TAX_AMOUNT']),
            'terms_of_payment' => @$data['objKeys']['termsOfPayment'],
            'custom_notes_field' => @$data['objKeys']['customNotesField'],
            'draft_status_change_date' => Carbon::parse($data['objKeys']['ACCOUNTING_DATE']),
            'contact_person' => @$data['objKeys']['contactPerson'],
            'receiver_type' => @$data['objKeys']['receiverType'],
            'payment_terms' => @$data['objKeys']['paymentTerms']
        ]);
        return $creditor_invoice;
    }

    /**
     * Update the specified creditor_invoice.
     *
     * @param  \App\Models\CreditorInvoice  $creditor_invoice
     * @param  array  $data
     * @return \App\Models\CreditorInvoice
     */
    public function updateCreditorInvoice(CreditorInvoice $creditor_invoice, array $data)
    {
        $company = Company::where('company_name', 'LIKE', '%' . $data['objKeys']['COMPANY_NAME'] . '%')->firstOrFail();
        $creditor_invoice->update([
            'invoice_number' => @$data['objKeys']['ACCOUNTING_NUMBER'],
            'invoice_type' => @$data['objKeys']['invoiceType'],
            'due_date' => Carbon::parse($data['objKeys']['ACCOUNTING_DUE_DATE']),
            'start_date' => Carbon::parse($data['objKeys']['ACCOUNTING_DATEV_FISCALYEAR']),
            'end_date' => Carbon::parse($data['objKeys']['ACCOUNTING_DATEV_POSTINGPROPOSAL_CREATED']),
            'company_id' => $company->id,
            'user_id' => @$data['objKeys']['userId'],
            'invoice_period' => @$data['objKeys']['invoicePeriod'],
            'total_amount' => $this->convertGermanNumberToDecimal($data['objKeys']['ACCOUNTING_TOTAL_AMOUNT']),
            'total_amount_without_tax' => $this->convertGermanNumberToDecimal($data['objKeys']['ACCOUNTING_NET_AMOUNT']),
            'total_tax_amount' => $this->convertGermanNumberToDecimal($data['objKeys']['ACCOUNTING_TAX_AMOUNT']),
            'terms_of_payment' => @$data['objKeys']['termsOfPayment'],
            'custom_notes_field' => @$data['objKeys']['customNotesField'],
            'draft_status_change_date' => Carbon::parse($data['objKeys']['ACCOUNTING_DATE']),
            'contact_person' => @$data['objKeys']['contactPerson'],
            'receiver_type' => @$data['objKeys']['receiverType'],
            'payment_terms' => @$data['objKeys']['paymentTerms']
        ]);
        return $creditor_invoice;
    }

    /**
     * Delete the specified creditor_invoice.
     *
     * @param  \App\Models\CreditorInvoice  $fleetCar
     * @return void
     */
    public function deleteCreditorInvoice(CreditorInvoice $creditor_invoice)
    {
        $creditor_invoice->delete();
    }
}
