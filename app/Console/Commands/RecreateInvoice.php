<?php

namespace App\Console\Commands;

use App\Jobs\CreateInvoiceJob;
use Illuminate\Console\Command;

class RecreateInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recreate:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recreates the invoice according to the recreate after field value';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        CreateInvoiceJob::dispatch();

        $this->info('Create Invoice has been dispatched');
    }
}
