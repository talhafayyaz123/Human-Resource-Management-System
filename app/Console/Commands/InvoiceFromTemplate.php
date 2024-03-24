<?php

namespace App\Console\Commands;

use App\Jobs\CreateInvoiceFromTemplate;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InvoiceFromTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:invoice-from-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create invoice from the invoice templates on the bases of next create date';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): void
    {
        $invoiceTemplates = Invoice::where('is_invoice_template', 1)
            ->where('next_create_date', Carbon::now()->toDateString())
            ->get();
        foreach ($invoiceTemplates as $invoiceTemplate) {
            CreateInvoiceFromTemplate::dispatch($invoiceTemplate);
        }
    }
}
