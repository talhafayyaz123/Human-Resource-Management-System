<?php

namespace App\Jobs;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateInvoiceFromTemplate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invoiceTemplate;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoiceTemplate)
    {
        $this->invoiceTemplate = $invoiceTemplate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invoice = $this->invoiceTemplate;
        $new_invoice = new Invoice;

        DB::transaction(function () use ($invoice, &$new_invoice) {
            $now = Carbon::now();
            $new_invoice = $invoice->replicate();
            $created_at = Carbon::parse($invoice->created_at);
            if (isset($invoice->end_date)) {
                $end_date = Carbon::parse($invoice->end_date);
                $difference = $created_at->diffInDays($end_date);
                $new_invoice->end_date = $now->copy()->addDays($difference);
            }
            if (isset($invoice->due_date)) {
                $due_date = Carbon::parse($invoice->due_date);
                $difference = $created_at->diffInDays($due_date);
                $new_invoice->due_date = $now->copy()->addDays($difference);
            }
            if (isset($invoice->draft_status_change_date)) {
                $draft_status_change_date = Carbon::parse($invoice->draft_status_change_date);
                $difference = $created_at->diffInDays($draft_status_change_date);
                $new_invoice->draft_status_change_date = $now->copy()->addDays($difference);
            }
            if (isset($invoice->start_date)) {
                $start_date = Carbon::parse($invoice->start_date);
                $difference = $created_at->diffInDays($start_date);
                $new_invoice->start_date = $now->copy()->addDays($difference);
            }

            $new_invoice->status = InvoiceStatus::DRAFT;
            $new_invoice->next_create_date = null;
            $new_invoice->is_invoice_template = 0;
            $new_invoice->recreate_after = null;
            $new_invoice->save();
            foreach ($invoice->products as $product) {
                $replicate_product = $product->replicate();
                $replicate_product->save(); // Save the new product first to generate a new ID
                // Attach the product to the new invoice with the pivot data
                $new_invoice->products()->attach(
                    $replicate_product->id,
                    [
                        'sale_price' => $product->pivot->sale_price,
                        'discount' => $product->pivot->discount,
                        'total_price' => $product->pivot->total_price,
                        'quantity' => $product->pivot->quantity,
                        'tax' => $product->pivot->tax,
                        'price_without_tax' => $product->pivot->price_without_tax,
                        'maintenance_rate' => $product->pivot->maintenance_rate,
                        'hourly_rate' => $product->pivot->hourly_rate,
                        'service_hours' => $product->pivot->service_hours,
                        'payment_period' => $product->pivot->payment_period,
                        'price_per_period' => $product->pivot->price_per_period,
                        'license_id' => $product->pivot->license_id,
                        'duration' => $product->pivot->duration,
                        'additional_description' => $product->pivot->additional_description,
                        'parent_product_invoice_id' => $product->pivot->parent_product_invoice_id
                    ]
                );
                $product_invoice = $invoice->products()->latest()->first();
                $product->pivot->children?->map(function ($child) use ($new_invoice, $product_invoice) {

                    $new_invoice->products()->attach($child['id'], [
                        'sale_price' => $child['sale_price'], 'discount' => $child['discount'],
                        'quantity' => $child['quantity'], 'total_price' => isset($child['total_price']) ? $child['total_price'] : '',
                        'price_without_tax' => $child['price_without_tax'],
                        'tax' => $child['tax'],
                        'hourly_rate' => isset($child['hourly_rate']) ? $child['hourly_rate'] : null,
                        'price_per_period' => isset($child['price_per_period']) ? $child['price_per_period'] : null,
                        'service_hours' => isset($child['service_hours']) ? $child['service_hours'] : null,
                        'maintenance_rate' => isset($child['maintenance_rate']) ? $child['maintenance_rate'] : null,
                        'payment_period' => isset($child['payment_period']) ? $child['payment_period'] : null,
                        'duration' => isset($child['duration']) ? $child['duration'] : 1,
                        'additional_description' => isset($child["additional_description"]) ? $child["additional_description"] : null,
                        'parent_product_invoice_id' => $product_invoice->pivot->id
                    ]);
                });
            }
            foreach ($invoice->invoiceProductGroupByCategory as $product_category) {
                $replicate_product_category = $product_category->replicate();
                $new_invoice->invoiceProductGroupByCategory()->save($replicate_product_category);
            }
        });
        //calculate next create date for invoice template
        $recreate_after = $invoice->recreate_after;
        $nowDate = Carbon::now();
        if ($recreate_after == '2W') {
            $nextCreateDate = $nowDate->addWeeks(2);
        } else if ($recreate_after == '1M') {
            $nextCreateDate = $nowDate->addMonth();

        } else if ($recreate_after == '3M') {
            $nextCreateDate = $nowDate->addMonths(3);
        } else if ($recreate_after == '6M') {
            $nextCreateDate = $nowDate->addMonths(6);

        } else if ($recreate_after == '12M') {
            $nextCreateDate = $nowDate->addMonths(12);
        }

        $invoice->next_create_date = $nextCreateDate;
        $invoice->save();
        // You can return the new invoice if needed
        return $new_invoice;
    }
}
