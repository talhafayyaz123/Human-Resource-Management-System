<?php

namespace App\Services;

use App\Models\Product;
use App\Models\PaymentPeriod;
use App\Models\UserProfileData;
use Carbon\Carbon;

class InvoiceService
{
    /**
     * create the invoice payload for document generation
     * @param Invoice $invoice
     * @return array
     */
    public static function invoiceForDocumentGeneration($invoice)
    {
        $user_profile = UserProfileData::where('user_id', $invoice->user_id)->first();
        $invoice_data = [
            'id' => $invoice->id,
            'invoiceNumber' => $invoice->invoice_number,
            'reminderLevelId' => $invoice?->reminder_level_id ?? null,
            'applyReverseChange' => $invoice->apply_reverse_change,
            'invoiceType' => $invoice->invoice_type,
            'dueDate' => Carbon::parse($invoice->due_date)->format('Y-m-d'),
            'startDate' => $invoice->start_date ? Carbon::parse($invoice->start_date)->format('Y-m-d') : null,
            'endDate' => $invoice->end_date ? Carbon::parse($invoice->end_date)->format('Y-m-d') : null,
            'invoicePeriod' => $invoice->invoice_period,
            'invoicePeriodName' => $invoice->paymentPeriod ? $invoice->paymentPeriod->name : "-",
            'externalOrderNumber' => $invoice->external_order_number,
            'termsOfPayment' => $invoice->termsOfPayment ? [
                'id' => $invoice->termsOfPayment->id,
                "noOfDays1" => $invoice->termsOfPayment->no_of_days_1,
                "percentage1" => $invoice->termsOfPayment->percentage_1,
                "noOfDays2" => $invoice->termsOfPayment->no_of_days_2,
                "percentage2" => $invoice->termsOfPayment->percentage_2,
                "percentage3" => $invoice->termsOfPayment->percentage_3,
                "percentage3" => $invoice->termsOfPayment->percentage_3,
                "name" => $invoice->termsOfPayment->name,
                "offerText" => $invoice->termsOfPayment->offer_text,
                "orderText" => $invoice->termsOfPayment->order_text,
                "invoiceText" => $invoice->termsOfPayment->invoice_text,
                "paymentTerms" => $invoice->termsOfPayment->payment_terms
            ] : null,
            'termsOfPaymentName' => $invoice->termsOfPayment ? $invoice->termsOfPayment->name . " | " .
                $invoice->termsOfPayment->payment_terms : "-",
            'customNotesFields' => $invoice->custom_notes_field,
            'projectId' => $invoice?->project ?? null,
            'groupedBy' => $invoice->grouped_by ?? null,
            'paymentTerms' => $invoice->payment_terms ?? "",
            'performanceRecord' => $invoice->performanceRecord ?
                [
                    'id' => $invoice->performanceRecord->id,
                    'company' => $invoice->performanceRecord->customer?->company_name,
                    'companyId' => $invoice->performanceRecord->customer?->id,
                    'advisorId' => $invoice->performanceRecord->advisor_id,
                    'companyNumber' => $invoice->performanceRecord->customer?->company_number,
                    'defaultServiceProduct' => $invoice->performanceRecord->customer?->default_service_product ?? null,
                    'defaultServiceHourlyRate' => $invoice->performanceRecord->customer?->default_service_hourly_rate ?? null,
                    'defaultServiceDailyRate' => $invoice->performanceRecord->customer?->default_service_daily_rate ?? null,
                    'invoice' => $invoice->performanceRecord?->invoice,
                    'totalHours' => $invoice->performanceRecord->total_hours,
                    'goodWillHours' => $invoice->performanceRecord->good_will_hours,
                    'startDate' => $invoice->performanceRecord->start_date,
                    'endDate' => $invoice->performanceRecord->end_date,
                    'createdDate' => Carbon::parse($invoice->performanceRecord->created_at)->format('Y-m-d'),
                    'performanceNumber' => $invoice->performanceRecord->performance_number,
                    'status' => $invoice->performanceRecord->status,
                    'updatedAt' =>  Carbon::parse($invoice->performanceRecord->updated_at)->toDateString(),
                    'editedUserId' =>  $invoice->performanceRecord->edited_user_id,
                ]
                : "",
            'totalAmountWithoutTax' => $invoice->total_amount_without_tax,
            'totalTaxAmount' => $invoice->total_tax_amount,
            'totalAmount' => $invoice->total_amount,
            'createdAt' => Carbon::parse($invoice->created_at)->toDateString(),
            'contactPerson' => $invoice->contact_person ?? "",
            'draftStatusChangeDate' => isset($invoice->draft_status_change_date) ? Carbon::parse($invoice->draft_status_change_date)->format('Y-m-d') : '',
            'invoiceDate' => isset($invoice->draft_status_change_date) ? Carbon::parse($invoice->draft_status_change_date)->format('Y-m-d') : '',
            'products' => $invoice->products()->whereNull('parent_product_invoice_id')->get()->map(function ($product) {
                $payment_period = PaymentPeriod::find($product->pivot->payment_period);
                return [
                    'id' => $product->id,
                    'productInvoiceId' => $product->pivot->id, //productInvoiceId is being used for additional description toggling purposes since it is unique
                    'name' => $product->pivot->product_name ??  $product->name,
                    'description' => $product->description,
                    'articleNumber' => $product->article_number,
                    'salePrice' => floatval($product->pivot->sale_price),
                    'discount' => floatval($product->pivot->discount),
                    'additionalDescription' => $product->pivot->additional_description,
                    'status' => $product->status ? 'active' : 'deactive',
                    'listingPrice' => $product->listing_price,
                    'profit' => $product->profit,
                    'quantity' => floatval($product->pivot->quantity),
                    'totalPrice' => floatval($product->pivot->total_price),
                    'tax' => +$product->pivot->tax,
                    'priceWithoutTax' => floatval($product->pivot->price_without_tax),
                    'maintenancePrice' => (floatval($product->pivot->sale_price) * $product->pivot->quantity * floatval($product->pivot->maintenance_rate)) / 100,
                    'baseMaintenancePrice' => (floatval($product->pivot->sale_price) *
                        floatval($product->pivot->maintenance_rate)) /
                        100,
                    'totalSalePriceAdjustedForQuantity' => floatval($product->pivot->sale_price) * $product->pivot->quantity,
                    'maintenanceRate' => floatval($product->pivot->maintenance_rate),
                    'productSoftware' => $product->productSoftware,
                    'unit' => $product->productUnit ? [
                        'id' => $product->productUnit->id,
                        'name' => $product->productUnit->name,
                        'shortName' => $product->productUnit->short_name,
                    ] : null,
                    'type' => $product->payment_details_type,
                    'dailyRate' => $product->daily_rate,
                    'serviceDays' => $product->service_days,
                    'serviceHours' => $product->pivot->service_hours,
                    'hourlyRate' => $product->pivot->hourly_rate,
                    'pricePerPeriod' => $product->pivot->price_per_period,
                    'paymentPeriod' => $payment_period ? [
                        'id' => $payment_period->id,
                        'name' => $payment_period->name,
                        'billingCycle' => $payment_period->billing_cycle,
                        'createdAt' => Carbon::parse($payment_period->created_at)->format('d/m/y')
                    ] : null,
                    'duration' => $product->pivot->duration,
                    'category' => [
                        "id" => $product?->productCategory?->id,
                        "name" => $product?->productCategory?->name,
                        "defaultUnit" => $product?->productCategory?->default_unit,
                        "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                    ],
                    'children' => $product->pivot->children?->map(function ($child) {
                        $child_product = Product::find($child->product_id);
                        return [
                            'id' => $child_product->id,
                            'productInvoiceId' => $child->id, //productInvoiceId is being used for additional description toggling purposes since it is unique
                            'name' => $child_product->name,
                            'description' => $child_product->description,
                            'articleNumber' => $child_product->article_number,
                            'salePrice' => floatval($child->sale_price),
                            'discount' => floatval($child->discount),
                            'additionalDescription' => $child->additional_description,
                            'status' => $child_product->status ? 'active' : 'deactive',
                            'listingPrice' => $child_product->listing_price,
                            'profit' => $child_product->profit,
                            'quantity' => floatval($child->quantity),
                            'totalPrice' => floatval($child->total_price),
                            'tax' => +$child->tax,
                            'priceWithoutTax' => floatval($child->price_without_tax),
                            'maintenancePrice' => $child_product->maintanence_price,
                            'maintenanceRate' => floatval($child->maintenance_rate),
                            'productSoftware' => $child_product->productSoftware,
                            'unit' => $child_product->productUnit ? [
                                'id' => $child_product->productUnit->id,
                                'name' => $child_product->productUnit->name,
                                'shortName' => $child_product->productUnit->short_name,
                            ] : null,
                            'type' => $child_product->payment_details_type,
                            'dailyRate' => $child_product->daily_rate,
                            'serviceDays' => $child_product->service_days,
                            'serviceHours' => $child->service_hours,
                            'hourlyRate' => $child->hourly_rate,
                            'pricePerPeriod' => $child->price_per_period,
                            'paymentPeriod' => $child_product->paymentPeriod ? [
                                'id' => $child_product->paymentPeriod->id,
                                'name' => $child_product->paymentPeriod->name,
                                'billingCycle' => $child_product->paymentPeriod->billing_cycle,
                                'createdAt' => Carbon::parse($child_product->paymentPeriod->created_at)->format('d/m/y')
                            ] : null,
                            'duration' => $child->duration,
                            'category' => [
                                "id" => $child_product?->productCategory?->id,
                                "name" => $child_product?->productCategory?->name,
                                "defaultUnit" => $child_product?->productCategory?->default_unit,
                                "isDefaultOnOffers" => $child_product?->productCategory?->is_default_on_offers
                            ],
                            'productGroup' => $child_product->productGroup?->name,
                        ];
                    })
                ];
            }),
            'customers' => [
                'id' => $invoice->company?->id,
                'companyName' => $invoice->company?->company_name,
                'vatId' => $invoice->company?->vat_id,
                'url' => $invoice->company?->url,
                'type' => $invoice->company?->type,
                'customerType' => $invoice->company?->customer_type,
                'companyNumber' => $invoice->company?->company_number,
                'state' => $invoice->company?->headQuarters?->state,
                'city' => $invoice->company?->headQuarters?->city,
                'country' => $invoice->company?->headQuarters?->country,
                'address' => $invoice->company?->headQuarters?->address_first,
                'code' => $invoice->company?->headQuarters?->zip,
                'notes' => $invoice->company?->notes,
                'status' => $invoice->company?->status,
                'expiryDate' => $invoice->company?->expiry_dt ? Carbon::parse($invoice->company?->expiry_dt)->toDateString() : '',
                'termsOfPayment' => $invoice->company?->terms_of_payment,
                'mergePdfsOnDefault' => $invoice->company?->merge_pdfs_on_default ?? 0
            ],
            'systems' => $invoice?->systems ? [
                'id' => $invoice?->systems?->id,
                'serverIp' => $invoice?->systems?->server_ip,
                'type' => $invoice?->systems?->type,
                'serverPort' =>  $invoice?->systems?->server_port,
                'username' => $invoice?->systems?->username,
                'systemNumber' => $invoice?->systems?->system_number
            ] : [],
            'customer_city' => $invoice->company->headQuarters?->city,
            'customer_country' => $invoice->company->headQuarters?->country,
            'customer_code' => $invoice->company->headQuarters?->zip,
            'customer_address' => $invoice->company->headQuarters?->address_first,
            'invoiceStatus' => $invoice->status,
            'category' => $invoice->invoice_category,
            'userId' => $invoice->user_id,
            'invoiceStatus' => $invoice->status,
            'output' => 'pdf',
            'userFirstName' => $user_profile?->first_name,
            'userLastName' => $user_profile?->last_name,
            'userEmail' => $user_profile?->email,
            'userPhone' => $user_profile?->phone ?? $user_profile?->mobile,
            'updatedTime' => Carbon::now()->timestamp
        ];

        $invoice_data['categories'] = [];

        if ($invoice->invoice_category == 'service' && $invoice->grouped_by == 'category') {
            $invoice_data['categories'] = $invoice->invoiceProductGroupByCategory->map(function ($item) use (&$invoice_data) {
                $products = [];
                foreach ($item->product_ids as $product_id) {
                    $product = Product::findOrFail($product_id);
                    array_push(
                        $products,
                        [
                            'id' => $product->id,
                            'name' => $product->name,
                            'description' => $product->description,
                            'articleNumber' => $product->article_number,
                            'salePrice' => floatval($product->sale_price),
                            'discount' => floatval($product->discount),
                            'status' => $product->status ? 'active' : 'deactive',
                            'listingPrice' => $product->listing_price,
                            'profit' => $product->profit,
                            'quantity' => floatval($product->quantity),
                            'totalPrice' => floatval($product->total_price),
                            'tax' => +$product->tax,
                            'priceWithoutTax' => floatval($product->price_without_tax),
                            'maintenancePrice' => (floatval($product->sale_price) * $product->quantity * floatval($product->maintenance_rate)) / 100,
                            'baseMaintenancePrice' => (floatval($product->sale_price) *
                                floatval($product->maintenance_rate)) /
                                100,
                            'totalSalePriceAdjustedForQuantity' =>
                            floatval($product->sale_price) * $product->quantity,
                            'maintenanceRate' => floatval($product->maintenance_rate),
                            'productSoftware' => $product->productSoftware,
                            'unit' => $product->productUnit ? [
                                'id' => $product->productUnit->id,
                                'name' => $product->productUnit->name,
                                'shortName' => $product->productUnit->short_name,
                            ] : null,
                            'type' => $product->payment_details_type,
                            'dailyRate' => $product->daily_rate,
                            'serviceDays' => $product->service_days,
                            'serviceHours' => $product->service_hours,
                            'hourlyRate' => $product->hourly_rate,
                            'pricePerPeriod' => $product->price_per_period,
                            'paymentPeriod' => $product->payment_period,
                            'duration' => $product->duration,
                            'category' => [
                                "id" => $product?->productCategory?->id,
                                "name" => $product?->productCategory?->name,
                                "defaultUnit" => $product?->productCategory?->default_unit,
                                "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                            ]
                        ]
                    );
                }
                $invoice_data['products'] = $products;
                return [
                    'categoryId' => $item->category->id,
                    'categoryName' => $item->category->name,
                    'quantity' => floatval($item->quantity),
                    'hourlyRate' => floatval($item->hourly_rate),
                    'discount' => floatval($item->discount),
                    'tax' => floatval($item->tax),
                    'taxRate' => floatval($item->tax_rate),
                    'nettoTotal' => floatval($item->netto_total),
                    'additionalDescription' => $item->additional_description,
                    'products' => $products
                ];
            });
        }
        return $invoice_data;
    }

    /**
     * create the performance record payload for document generation
     * @param PerformanceRecord $performance_record
     * @return array
     */
    public function performanceRecordForDocumentGeneration($performance_record)
    {
        $performance_record_data = null;
        $user_profile = null;

        if ($performance_record) {
            // Retrieve the user profile data for all entries
            $entryUserIds = $performance_record->performanceRecordEntries->pluck('user_id')->unique();
            $userProfileData = UserProfileData::whereIn('user_id', $entryUserIds)->get()->keyBy('user_id');

            // Initialize arrays for tasks, ticket comments, ams and new entries
            $tasks = [];
            $ticket_comments = [];
            $ams = [];
            $new_entry = [];

            $total_hours = 0;
            $total_goodwill_hours = 0;

            // Process each entry
            foreach ($performance_record->performanceRecordEntries as $entry) {


                $time_tracker_company = $entry->timeTrackerCompany;

                if ($entry->is_goodwill == 0) {
                    $total_hours += $entry->time;
                } else {
                    $total_goodwill_hours += $entry->time;
                }

                // Check if the module is a task
                if (!is_null($time_tracker_company) && $time_tracker_company->module_type === "App\Models\Task") {
                    $task = $time_tracker_company->module;
                    $milestone = $task->milestone;
                    $project = $milestone->project;

                    // Retrieve the user's first name and last name from the pre-fetched data
                    $user_profile = $userProfileData->get($entry->user_id);
                    $person = $user_profile ? ($user_profile->first_name . ' ' . $user_profile->last_name) : '';

                    // Create the mapped task object
                    $mapped_task = [
                        'id' => $entry->id,
                        'taskId' => $task->id,
                        'userId' => $entry->user_id,
                        'order' => floatval($entry->entry_order),
                        'date' => $entry->date,
                        'person' => $person,
                        'description' => $entry->description,
                        'internalNote' => $entry->internal_note,
                        'quantity' => $entry->time,
                        'isGoodwill' => $entry->is_goodwill,
                        'milestone' => $milestone,
                        'project' => $project,
                    ];

                    $tasks[] = $mapped_task;
                }
                // Check if the module is a ticket comment
                elseif (!is_null($time_tracker_company) && $time_tracker_company->module_type === "App\Models\TicketComment") {
                    $ticket_comment = $time_tracker_company->module;

                    // Retrieve the user's first name and last name from the pre-fetched data
                    $user_profile = $userProfileData->get($entry->user_id);
                    $person = $user_profile ? ($user_profile->first_name . ' ' . $user_profile->last_name) : '';

                    // Create the mapped ticket comment object
                    $mapped_ticket_comment = [
                        'id' => $entry->id,
                        'ticketCommentId' => $ticket_comment->id,
                        'userId' => $entry->user_id,
                        'order' => floatval($entry->entry_order),
                        'date' => $entry->date,
                        'person' => $person,
                        'description' => $entry->description,
                        'internalNote' => $entry->internal_note,
                        'quantity' => $entry->time,
                        'isGoodwill' => $entry->is_goodwill,
                        'ticketNumber' => optional($ticket_comment->ticket)->ticket_number,
                    ];

                    $ticket_comments[] = $mapped_ticket_comment;
                }
                // Check if the module is ams
                elseif (!is_null($time_tracker_company) && $time_tracker_company->module_type === "App\Models\ApplicationManagementService") {
                    $amsEntry = $time_tracker_company->module;

                    // Retrieve the user's first name and last name from the pre-fetched data
                    $user_profile = $userProfileData->get($entry->user_id);
                    $person = $user_profile ? ($user_profile->first_name . ' ' . $user_profile->last_name) : '';

                    // Create the mapped ams object
                    $mapped_ams = [
                        'id' => $entry->id,
                        'amsId' => $amsEntry->id,
                        'userId' => $entry->user_id,
                        'order' => floatval($entry->entry_order),
                        'date' => $entry->date,
                        'person' => $person,
                        'description' => $entry->description,
                        'internalNote' => $entry->internal_note,
                        'quantity' => $entry->time,
                        'isGoodwill' => $entry->is_goodwill,
                    ];

                    $ams[] = $mapped_ams;
                }
                // Check if the module is a newEntry with null module ID
                else {
                    // Retrieve the user's first name and last name from the pre-fetched data
                    $user_profile = $userProfileData->get($entry->user_id);
                    $person = $user_profile ? ($user_profile->first_name . ' ' . $user_profile->last_name) : '';

                    // Create the mapped new entry object
                    $mapped_new_entry = [
                        'id' => $entry->id,
                        'order' => floatval($entry->entry_order),
                        'userId' => $entry->user_id,
                        'date' => $entry->date,
                        'person' => $person,
                        'userProfile' => $user_profile,
                        'description' => $entry->description,
                        'internalNote' => $entry->internal_note,
                        'quantity' => $entry->time,
                        'isGoodwill' => $entry->is_goodwill,
                    ];

                    $new_entry[] = $mapped_new_entry;
                }
            }

            // Group tasks by project and milestone
            $tasks_mapped = collect($tasks)->groupBy('project.id')->map(function ($project_tasks) {
                $project = $project_tasks->first()['project'];

                $milestones_mapped = $project_tasks->groupBy('milestone.id')->map(function ($milestone_tasks) {
                    $milestone = $milestone_tasks->first()['milestone'];

                    $tasks_mapped = $milestone_tasks->map(function ($task) {
                        unset($task['project'], $task['milestone']);
                        return $task;
                    })->values();

                    return [
                        'milestoneId' => $milestone['id'],
                        'name' => $milestone['name'],
                        'tasks' => $tasks_mapped,
                    ];
                })->values();

                return [
                    'projectId' => $project['id'],
                    'name' => $project['name'],
                    'milestones' => $milestones_mapped,
                ];
            })->values();

            $performance_record_data = [
                'id' => $performance_record->id,
                'company' => $performance_record->customer->company_name,
                'companyNumber' => $performance_record->customer->company_number,
                'addressFirst' => $performance_record->customer->headQuarters?->address_first,
                'city' => $performance_record->customer->headQuarters?->city,
                'country' => $performance_record->customer->headQuarters?->country,
                'state' => $performance_record->customer->headQuarters?->state,
                'zip' => $performance_record->customer->headQuarters?->zip,
                'companyId' => $performance_record->customer->id,
                'projectId' => $performance_record->project_id,
                'performanceNumber' => $performance_record->performance_number,
                'startDate' => $performance_record->start_date,
                'endDate' => $performance_record->end_date,
                'advisor' => $performance_record->advisor_id,
                'createdDate' => Carbon::parse($performance_record->created_at)->format('Y-m-d'),
                'isEdited' =>  $performance_record->is_edited,
                'updatedAt' =>  Carbon::parse($performance_record->updated_at)->toDateString(),
                'editedUserId' =>  $performance_record->edited_user_id,
                'tasks' => $tasks_mapped,
                'ticketComments' => $ticket_comments,
                'ams' => $ams,
                'newEntry' => $new_entry,
                'totalHours' => $total_hours,
                'totalGoodwillHours' => $total_goodwill_hours,
                'status' => $performance_record->status,
                'output' => 'pdf',
                'userFirstName' => $user_profile?->first_name,
                'userLastName' => $user_profile?->last_name,
                'userPhone' => $user_profile?->phone,
                'updatedTime' => Carbon::now()->timestamp
            ];
        }
        return $performance_record_data;
    }
}
