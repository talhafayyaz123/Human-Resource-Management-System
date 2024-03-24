<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;
use App\Models\PaymentPeriod;
use App\Models\SaleOffer;
use App\Models\ProductCategory;
use App\Models\UserProfileData;
use Carbon\Carbon;

class OutboundedContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'contractNumber' => $this->contract_number,
            'receiverType' => $this->receiver_type,
            'status' => $this->status,
            'personInCharge' => $this->personInCharge ? [
                'id' => $this->personInCharge->id,
                'employee' => ($this->personInCharge->first_name ?? "") . ' ' . ($this->personInCharge->last_name ?? "")
            ] : null,
            'customer' => $this->receiver ? [
                'id' => $this->receiver->id,
                'companyName' => $this->receiver->company_name,
                'address' => $this->receiver->headQuarters?->address_first,
                'code' => $this->receiver->headQuarters?->zip,
                'city' => $this->receiver->headQuarters?->city,
                'state' => $this->receiver->headQuarters?->state,
                'country' => $this->receiver->headQuarters?->country
            ] : null,
            'contractType' => $this->contractType ? [
                'id' => $this->contractType->id,
                'name' => $this->contractType->name,
            ] : null,
            'createdAt' => Carbon::parse($this->created_at)->toDateString(),
            'attachments' => $this->attachments->map(function ($attachment) {
                $price_per_month = 0;
                return [
                    'id' => $attachment->id,
                    'name' => $attachment->name,
                    'contractAttachmentId' => $attachment->pivot->id,
                    // 'attachmentNumber' => $attachment->pivot->attachment_number,
                    'attachmentNumber' =>$this->contract_number,
                    'addProductsToCustomerLicenses' => $attachment->add_products_to_customer_licences,
                    'prefix' => $attachment->prefix,
                    'version' => $attachment->version,
                    'template' => $attachment->template,
                    'allowToAddSlas' => $attachment->allow_to_add_slas,
                    'selectUser' => $attachment->is_select_user,
                    'software' => $attachment->software ? [
                        'id' => $attachment->software->id,
                        'name' => $attachment->software->name,
                    ] : null,
                    'contractFields' => $attachment->pivot->selectedContractFields->map(function ($contract_field) use ($attachment) {
                        if ($contract_field->contractField->type == 'text') {
                            $value = $contract_field->text;
                        } else if ($contract_field->contractField->type == 'number') {
                            $value = $contract_field->number;
                        } else if ($contract_field->contractField->type == 'date') {
                            $value = Carbon::parse($contract_field->date)->toDateString();
                        } else if ($contract_field->contractField->type == 'time') {
                            $value = $contract_field->time;
                        } else if ($contract_field->contractField->type == 'customer') {
                            $value = $contract_field->customerModel ? [
                                'id' => $contract_field->customerModel->id,
                                'companyName' => $contract_field->customerModel->company_name,
                                'companyNumber' => $contract_field->customerModel->company_number,
                                'vatId' => $contract_field->customerModel->vat_id,
                                'url' => $contract_field->customerModel->url,
                                'phone' => $contract_field->customerModel->phone,
                                'fax' => $contract_field->customerModel->fax,
                                'type' => $contract_field->customerModel->type,
                                'customerType' => $contract_field->customerModel->customer_type,
                                'termsOfPayment' => $contract_field->customerModel->terms_of_payment,
                                'invoiceEmail' => $contract_field->customerModel->invoice_email,
                                'defaultServiceProduct' => $contract_field->customerModel->default_service_product,
                                'defaultServiceHourlyRate' => $contract_field->customerModel->default_service_hourly_rate,
                                'defaultServiceDailyRate' => $contract_field->customerModel->default_service_daily_rate,
                                'mergePdfsOnDefault' => $contract_field->customerModel->merge_pdfs_on_default,
                                'applyReverseChange' => $contract_field->customerModel->apply_reverse_change,
                                'discount' => $contract_field->customerModel->discount,
                                'expiryDate' => $contract_field->customerModel->expiry_dt ? Carbon::parse($contract_field->customerModel->expiry_dt)->toDateString() : '',
                                'status' => $contract_field->customerModel->status,
                                'notes' => $contract_field->customerModel->notes,
                                'size' => $contract_field->customerModel->size,
                                'orderProbability' => $contract_field->customerModel->order_probability,
                                'contactSources' => $contract_field->customerModel->contactReportSources,
                                'externalOrderNumber' => $contract_field->customerModel->external_order_number,
                                'defaultPaymentPeriod' => $contract_field->customerModel->defaultPaymentPeriod ? [
                                    'id' => $contract_field->customerModel->defaultPaymentPeriod->id,
                                    'name' => $contract_field->customerModel->defaultPaymentPeriod->name
                                ] : null,
                                'bankDetails' => isset($contract_field->customerModel->bankDetails) ? $contract_field->customerModel->bankDetails->map(function ($bank_detail) {
                                    return [
                                        'bankName' => $bank_detail->bank_name,
                                        'swift' => $bank_detail->swift,
                                        'iban' => $bank_detail->iban,
                                    ];
                                }) : [],
                                'priceLists' => isset($contract_field->customerModel->priceLists) ? $contract_field->customerModel->priceLists->map(function ($price) {
                                    return [
                                        'id' => $price->id,
                                        'name' => $price->name,
                                        'isDefault' => $price->is_default,
                                        'status' => $price->status,
                                        'productSoftwareName' => $price->productSoftware?->name,
                                        'productSoftwareId' => $price->product_software_id,
                                    ];
                                }) : [],
                                'assignees' => isset($contract_field->customerModel->assignees) ? $contract_field->customerModel->assignees->map(function ($assignee) {
                                    return [
                                        'id' => $assignee->user_id
                                    ];
                                }) : [],
                                'state' => $contract_field->customerModel->headQuarters?->state,
                                'city' => $contract_field->customerModel->headQuarters?->city,
                                'country' => $contract_field->customerModel->headQuarters?->country,
                                'address' => $contract_field->customerModel->headQuarters?->address_first,
                                'code' => $contract_field->customerModel->headQuarters?->zip,
                            ] : null;
                        } else if ($contract_field->contractField->type == 'invoice') {
                            $value = $contract_field->invoiceModel ? [
                                'id' => $contract_field->invoiceModel->id,
                                'invoiceNumber' => $contract_field->invoiceModel->invoice_number,
                                'invoiceType' => $contract_field->invoiceModel->invoice_type,
                                'dueDate' => Carbon::parse($contract_field->invoiceModel->due_date)->format('Y-m-d'),
                                'startDate' => $contract_field->invoiceModel->start_date ? Carbon::parse($contract_field->invoiceModel->start_date)->format('Y-m-d') : null,
                                'endDate' => $contract_field->invoiceModel->end_date ? Carbon::parse($contract_field->invoiceModel->end_date)->format('Y-m-d') : null,
                                'invoicePeriod' => $contract_field->invoiceModel->invoice_period,
                                'invoicePeriodName' => $contract_field->invoiceModel->paymentPeriod ? $contract_field->invoiceModel->paymentPeriod->name : "-",
                                'termsOfPayment' => $contract_field->invoiceModel->terms_of_payment,
                                'externalOrderNumber' => $contract_field->invoiceModel->external_order_number,
                                'termsOfPaymentName' => $contract_field->invoiceModel->termsOfPayment ? $contract_field->invoiceModel->termsOfPayment->name . " | " .
                                    $contract_field->invoiceModel->termsOfPayment->payment_terms : "-",
                                'termOfPaymentName' => $contract_field->invoiceModel->termsOfPayment?->name,
                                'notes' => $contract_field->invoiceModel->custom_notes_field,
                                'project' => $contract_field->invoiceModel?->project ?? null,
                                'reminderLevelId' => $contract_field->invoiceModel?->reminder_level_id ?? null,
                                'reminderStopUntil' => $contract_field->invoiceModel?->reminder_stop_until ?? null,
                                'reminderStop' => $contract_field->invoiceModel?->reminder_stop ?? null,
                                'groupedBy' => $contract_field->invoiceModel->grouped_by ?? null,
                                'paymentTerms' => $contract_field->invoiceModel->payment_terms ?? null,
                                'performanceRecord' => $contract_field->invoiceModel->performance_record_id,
                                'performanceRecordObject' => $contract_field->invoiceModel->performanceRecord,
                                'totalAmountWithoutTax' => $contract_field->invoiceModel->total_amount_without_tax,
                                'totalTaxAmount' => $contract_field->invoiceModel->total_tax_amount,
                                'totalAmount' => $contract_field->invoiceModel->total_amount,
                                'createdAt' => Carbon::parse($contract_field->invoiceModel->created_at)->toDateString(),
                                'contactPerson' => $contract_field->invoiceModel->contact_person,
                                'applyReverseChange' => $contract_field->invoiceModel->apply_reverse_change ?? 0,
                                'recreateAfter' => $contract_field->invoiceModel->recreate_after,
                                'draftStatusChangeDate' => isset($contract_field->invoiceModel->draft_status_change_date) ? Carbon::parse($contract_field->invoiceModel->draft_status_change_date)->format('Y-m-d') : '',
                                'products' => $contract_field->invoiceModel->products()->whereNull('parent_product_invoice_id')->get()->map(function ($product) {
                                    $payment_period = PaymentPeriod::find($product->pivot->payment_period);
                                    return [
                                        'id' => $product->id,
                                        'productInvoiceId' => $product->pivot->id, //productInvoiceId is being used for additional description toggling purposes since it is unique
                                        'name' => $product->name,
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
                                        'maintenancePrice' => $product->maintanence_price,
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
                                'customer' => [
                                    'id' => $contract_field->invoiceModel->company?->id,
                                    'companyName' => $contract_field->invoiceModel->company?->company_name,
                                    'vatId' => $contract_field->invoiceModel->company?->vat_id,
                                    'url' => $contract_field->invoiceModel->company?->url,
                                    'type' => $contract_field->invoiceModel->company?->type,
                                    'customerType' => $contract_field->invoiceModel->company?->customer_type,
                                    'companyNumber' => $contract_field->invoiceModel->company?->company_number,
                                    'state' => $contract_field->invoiceModel->company?->headQuarters?->state,
                                    'city' => $contract_field->invoiceModel->company?->headQuarters?->city,
                                    'country' => $contract_field->invoiceModel->company?->headQuarters?->country,
                                    'address' => $contract_field->invoiceModel->company?->headQuarters?->address_first,
                                    'code' => $contract_field->invoiceModel->company?->headQuarters?->zip,
                                    'notes' => $contract_field->invoiceModel->company?->notes,
                                    'status' => $contract_field->invoiceModel->company?->status,
                                    'expiryDate' => $contract_field->invoiceModel->company?->expiry_dt ? Carbon::parse($contract_field->invoiceModel->company?->expiry_dt)->toDateString() : '',
                                    'termsOfPayment' => $contract_field->invoiceModel->company?->terms_of_payment,
                                    'mergePdfsOnDefault' => $contract_field->invoiceModel->company?->merge_pdfs_on_default,
                                    'externalOrderNumber' => $contract_field->invoiceModel->company?->external_order_number

                                ],
                                'systems' => $contract_field->invoiceModel?->systems ? [
                                    'id' => $contract_field->invoiceModel?->systems?->id,
                                    'serverIp' => $contract_field->invoiceModel?->systems?->server_ip,
                                    'type' => $contract_field->invoiceModel?->systems?->type,
                                    'serverPort' => $contract_field->invoiceModel?->systems?->server_port,
                                    'username' => $contract_field->invoiceModel?->systems?->username,
                                    'systemNumber' => $contract_field->invoiceModel?->systems?->system_number
                                ] : null,
                                'customer_city' => $contract_field->invoiceModel->company->headQuarters?->city,
                                'customer_country' => $contract_field->invoiceModel->company->headQuarters?->country,
                                'customer_code' => $contract_field->invoiceModel->company->headQuarters?->zip,
                                'customer_address' => $contract_field->invoiceModel->company->headQuarters?->address_first,
                                'invoiceStatus' => $contract_field->invoiceModel->status,
                                'category' => $contract_field->invoiceModel->invoice_category,
                                'userId' => $contract_field->invoiceModel->user_id,
                            ] : null;
                        } else if ($contract_field->contractField->type == 'performance-record') {
                            if ($contract_field->performanceRecordModel) {
                                // Retrieve the user profile data for all entries
                                $entryUserIds = $contract_field->performanceRecordModel?->performanceRecordEntries?->pluck('user_id')?->unique() ?? collect([]);
                                $userProfileData = UserProfileData::whereIn('user_id', $entryUserIds)->get()->keyBy('user_id');

                                // Initialize arrays for tasks, ticket comments, ams and new entries
                                $tasks = [];
                                $ticket_comments = [];
                                $ams = [];
                                $new_entry = [];

                                $total_hours = 0;
                                $total_goodwill_hours = 0;

                                // Process each entry
                                foreach ($contract_field?->performanceRecordModel?->performanceRecordEntries ?? [] as $entry) {

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
                                            'timeCheckingStatus' => $time_tracker_company->time_checking_status
                                        ];

                                        $tasks[] = $mapped_task;
                                    } // Check if the module is a ticket comment
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
                                            'timeCheckingStatus' => $time_tracker_company->time_checking_status
                                        ];

                                        $ticket_comments[] = $mapped_ticket_comment;
                                    } // Check if the module is ams
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
                                            'timeCheckingStatus' => $time_tracker_company->time_checking_status
                                        ];

                                        $ams[] = $mapped_ams;
                                    } // Check if the module is a newEntry with null module ID
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
                                            'timeCheckingStatus' => $time_tracker_company?->time_checking_status
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
                                $value = [
                                    'id' => $contract_field->performanceRecordModel?->id,
                                    'company' => $contract_field->performanceRecordModel?->customer->company_name,
                                    'companyNumber' => $contract_field->performanceRecordModel?->customer->company_number,
                                    'addressFirst' => $contract_field->performanceRecordModel?->customer->headQuarters?->address_first,
                                    'city' => $contract_field->performanceRecordModel?->customer->headQuarters?->city,
                                    'country' => $contract_field->performanceRecordModel?->customer->headQuarters?->country,
                                    'state' => $contract_field->performanceRecordModel?->customer->headQuarters?->state,
                                    'zip' => $contract_field->performanceRecordModel?->customer->headQuarters?->zip,
                                    'companyId' => $contract_field->performanceRecordModel?->customer->id,
                                    'projectId' => $contract_field->performanceRecordModel?->project_id,
                                    'performanceNumber' => $contract_field->performanceRecordModel?->performance_number,
                                    'startDate' => $contract_field->performanceRecordModel?->start_date,
                                    'endDate' => $contract_field->performanceRecordModel?->end_date,
                                    'advisor' => $contract_field->performanceRecordModel?->advisor_id,
                                    'createdDate' => Carbon::parse($contract_field->performanceRecordModel?->created_at)->format('Y-m-d'),
                                    'isEdited' => $contract_field->performanceRecordModel?->is_edited,
                                    'updatedAt' => Carbon::parse($contract_field->performanceRecordModel?->updated_at)->toDateString(),
                                    'editedUserId' => $contract_field->performanceRecordModel?->edited_user_id,
                                    'tasks' => $tasks_mapped,
                                    'ticketComments' => $ticket_comments,
                                    'ams' => $ams,
                                    'newEntry' => $new_entry,
                                    'totalHours' => $total_hours,
                                    'totalGoodwillHours' => $total_goodwill_hours,
                                    'status' => $contract_field->performanceRecordModel?->status,
                                ];
                            }
                        } else if ($contract_field->contractField->type == 'offer') {
                            if ($attachment->add_products_to_customer_licences) {
                                $value = ($contract_field?->contractFieldProducts ?? [])?->map(function ($contract_field_product) {
                                    return $this->productDetail($contract_field_product);
                                });
                            } else {
                                $parent_offer = SaleOffer::find($contract_field->offerModel->offer_id);
                                $value = $contract_field->offerModel ? [
                                    'id' => $contract_field->offerModel->id,
                                    'offerType' => $contract_field->offerModel->type,
                                    'offerNumber' => $contract_field->offerModel->sale_offer_number,
                                    'coverLetterText' => $contract_field->offerModel->cover_letter_text,
                                    'offerDescriptionText' => $contract_field->offerModel->offer_description_text,
                                    'company' => $contract_field->offerModel->company->id,
                                    'term' => $contract_field->offerModel->term->id,
                                    'project' => $contract_field->offerModel->project?->id,
                                    'removeFromStatistics' => $contract_field->offerModel->remove_from_statistics,
                                    'parentOffer' => $parent_offer ? [
                                        'id' => $parent_offer->id,
                                        'offerNumber' => $parent_offer->sale_offer_number,
                                        'company' => $parent_offer->company->company_name,
                                        'terms' => $parent_offer->term->name,
                                        'receiverType' => $parent_offer->receiver_type,
                                        'project' => $parent_offer->project?->name,
                                        'type' => $parent_offer->offer_type,
                                        'totalNetto' => $parent_offer->components->sum('total_netto')
                                    ] : null,
                                    'type' => $contract_field->offerModel->offer_type,
                                    'receiverType' => $contract_field->offerModel->receiver_type,
                                    'groupedBy' => $contract_field->offerModel->grouped_by,
                                    'template' => $contract_field->offerModel->template,
                                    'createdBy' => $contract_field->offerModel->created_by,
                                    'expiryDate' => Carbon::parse($contract_field->offerModel->expiry_date)->toDateString(),
                                    'plannedStartDate' => Carbon::parse($contract_field->offerModel->planned_start_date)->toDateString(),
                                    'paymentTerms' => $contract_field->offerModel->payment_terms,
                                    'createdAt' => Carbon::parse($contract_field->offerModel->created_at)->toDateString(),
                                    'updatedAt' => Carbon::parse($contract_field->offerModel->updated_at)->toDateString(),
                                    'contactPerson' => $contract_field->offerModel->contact_person,
                                    'externalOrderNumber' => $contract_field->offerModel->external_order_number,
                                    'identifier' => $contract_field->offerModel->identifier,
                                    'components' => $contract_field->offerModel->components()->whereNull('parent_component_id')->get()->map(function ($component) use ($contract_field) {
                                        $productsGroupedByCategory = collect();
                                        if ($contract_field->offerModel->grouped_by == "category" && $component->type == "service") {
                                            // get all products part of this category, even duplicates
                                            foreach (($component?->products ?? []) as $product_id) {
                                                $product = Product::with('productCategory')->findOrFail($product_id);
                                                $productsGroupedByCategory->push($product);
                                            }
                                        }
                                        return [
                                            'id' => $component->id,
                                            'type' => $component->type,
                                            'product' => [
                                                'id' => $component->product->id,
                                                'articleNumber' => $component->product->article_number,
                                                'name' => $component->product->name,
                                                'listingPrice' => $component->product->listing_price,
                                                'status' => $component->product->status ? 'active' : 'deactive',
                                                'salePrice' => $component->product->sale_price,
                                                'profit' => $component->product->profit,
                                                'discount' => $component->product->discount ?? 0,
                                                'maintenancePrice' => $component->product->maintanence_price,
                                                'maintenanceRate' => $component->product->maintenance_rate,
                                                'quantity' => $component->product->quantity,
                                                'pricePerPeriod' => $component->product->price_per_period,
                                                'paymentPeriod' => $component->product->paymentPeriod,
                                                'productSoftware' => $component->product->productSoftware,
                                                'tax' => $component->product->tax,
                                                'type' => $component->product->payment_details_type,
                                                'unit' => $component->product->productUnit,
                                                'description' => $component->product->description,
                                                'dailyRate' => $component->product->daily_rate,
                                                'serviceDays' => $component->product->service_days,
                                                'category' => [
                                                    "id" => $component->product?->productCategory?->id,
                                                    "name" => $component->product?->productCategory?->name,
                                                    "defaultUnit" => $component->product?->productCategory?->default_unit,
                                                    "isDefaultOnOffers" => $component->product?->productCategory?->is_default_on_offers
                                                ]
                                            ],
                                            'quantity' => $component->quantity,
                                            'duration' => $component->duration,
                                            'hourlyRate' => $component->hourly_rate,
                                            'pricePerPeriod' => $component->price_per_period,
                                            'salePrice' => $component->sale_price,
                                            'maintenanceRate' => $component->maintenance_rate,
                                            'tax' => $component->tax,
                                            'paymentPeriod' => $component->payment_period,
                                            'discount' => $component->discount ?? 0,
                                            'additionalDescription' => $component->additional_description,
                                            'products' => $productsGroupedByCategory->map(function ($product) {
                                                return [
                                                    'id' => $product->id,
                                                    'articleNumber' => $product->article_number,
                                                    'name' => $product->name,
                                                    'listingPrice' => $product->listing_price,
                                                    'status' => $product->status ? 'active' : 'deactive',
                                                    'salePrice' => $product->sale_price,
                                                    'profit' => $product->profit,
                                                    'hourlyRate' => $product->hourly_rate,
                                                    'pricePerPeriod' => $product->price_per_period,
                                                    'dailyRate' => $product->daily_rate,
                                                    'unit' => $product->productUnit ? [
                                                        'id' => $product->productUnit->id,
                                                        'name' => $product->productUnit->name,
                                                        'shortName' => $product->productUnit->short_name,
                                                    ] : null,
                                                    'unitShortName' => $product->productUnit?->short_name,
                                                    'description' => $product->description,
                                                    'discount' => $product->discount ?? 0,
                                                    'maintenancePrice' => $product->maintanence_price,
                                                    'maintenanceRate' => $product->maintenance_rate,
                                                    'quantity' => $product->quantity,
                                                    'tax' => $product->tax,
                                                    'type' => $product->payment_details_type,
                                                    'category' => [
                                                        "id" => $product?->productCategory?->id,
                                                        "name" => $product?->productCategory?->name,
                                                        "defaultUnit" => $product?->productCategory?->default_unit,
                                                        "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                                                    ]
                                                ];
                                            }),
                                            'productCategory' => ProductCategory::where("id", $component->product_category_id)?->first(),
                                            'offerId' => $component->sale_offer_id,
                                            'slaInfrastructureId' => $component->slaInfrastructure ? [
                                                'id' => $component->slaInfrastructure->id,
                                                'name' => $component->slaInfrastructure->name,
                                                'access' => $component->slaInfrastructure->is_access ? true : false,
                                                'includedUsers' => $component->slaInfrastructure->user_included,
                                                'additionalUser' => $component->slaInfrastructure->additional_user_cost,
                                            ] : null,
                                            'slaServiceTimeId' => $component->slaServiceTime ? [
                                                'id' => $component->slaServiceTime->id,
                                                'name' => $component->slaServiceTime->name,
                                                'days' => $component->slaServiceTime->days,
                                                'from' => $component->slaServiceTime->from,
                                                'to' => $component->slaServiceTime->to,
                                            ] : null,
                                            'slaLevelId' => $component->slaLevel ? [
                                                'id' => $component->slaLevel->id,
                                                'name' => $component->slaLevel->name,
                                                'reactionTimeUrgent' => $component->slaLevel->reaction_time_urgent,
                                                'reactionTimeHigh' => $component->slaLevel->reaction_time_high,
                                                'reactionTimeLow' => $component->slaLevel->reaction_time_low,
                                            ] : null,
                                            'children' => $component->children->map(function ($child) use ($contract_field) {
                                                $productsGroupedByCategory = collect();
                                                if ($contract_field->offerModel->grouped_by == "category" && $child->type == "service") {
                                                    $productsGroupedByCategory = Product::with('productCategory')->whereIn('id', $child?->products)->get();
                                                }
                                                return [
                                                    'id' => $child->id,
                                                    'type' => $child->type,
                                                    'product' => [
                                                        'id' => $child->product->id,
                                                        'articleNumber' => $child->product->article_number,
                                                        'name' => $child->product->name,
                                                        'listingPrice' => $child->product->listing_price,
                                                        'status' => $child->product->status ? 'active' : 'deactive',
                                                        'salePrice' => $child->product->sale_price,
                                                        'profit' => $child->product->profit,
                                                        'discount' => $child->product->discount ?? 0,
                                                        'maintenancePrice' => $child->product->maintanence_price,
                                                        'maintenanceRate' => $child->product->maintenance_rate,
                                                        'quantity' => $child->product->quantity,
                                                        'pricePerPeriod' => $child->product->price_per_period,
                                                        'paymentPeriod' => $child->product->paymentPeriod,
                                                        'productSoftware' => $child->product->productSoftware,
                                                        'tax' => $child->product->tax,
                                                        'type' => $child->product->payment_details_type,
                                                        'unit' => $child->product->productUnit ? [
                                                            'id' => $child->product->productUnit->id,
                                                            'name' => $child->product->productUnit->name,
                                                            'shortName' => $child->product->productUnit->short_name,
                                                        ] : null,
                                                        'description' => $child->product->description,
                                                        'dailyRate' => $child->product->daily_rate,
                                                        'serviceDays' => $child->product->service_days,
                                                        'category' => [
                                                            "id" => $child->product?->productCategory?->id,
                                                            "name" => $child->product?->productCategory?->name,
                                                            "defaultUnit" => $child->product?->productCategory?->default_unit,
                                                            "isDefaultOnOffers" => $child->product?->productCategory?->is_default_on_offers
                                                        ]
                                                    ],
                                                    'quantity' => $child->quantity,
                                                    'duration' => $child->duration,
                                                    'hourlyRate' => $child->hourly_rate,
                                                    'pricePerPeriod' => $child->price_per_period,
                                                    'salePrice' => $child->sale_price,
                                                    'maintenanceRate' => $child->maintenance_rate,
                                                    'tax' => $child->tax,
                                                    'paymentPeriod' => $child->payment_period,
                                                    'discount' => $child->discount ?? 0,
                                                    'products' => $productsGroupedByCategory->map(function ($product) {
                                                        return [
                                                            'id' => $product->id,
                                                            'articleNumber' => $product->article_number,
                                                            'name' => $product->name,
                                                            'listingPrice' => $product->listing_price,
                                                            'status' => $product->status ? 'active' : 'deactive',
                                                            'salePrice' => $product->sale_price,
                                                            'profit' => $product->profit,
                                                            'hourlyRate' => $product->hourly_rate,
                                                            'pricePerPeriod' => $product->price_per_period,
                                                            'dailyRate' => $product->daily_rate,
                                                            'unit' => $product->productUnit ? [
                                                                'id' => $product->productUnit->id,
                                                                'name' => $product->productUnit->name,
                                                                'shortName' => $product->productUnit->short_name,
                                                            ] : null,
                                                            'unitShortName' => $product->productUnit?->short_name,
                                                            'description' => $product->description,
                                                            'discount' => $product->discount ?? 0,
                                                            'maintenancePrice' => $product->maintanence_price,
                                                            'maintenanceRate' => $product->maintenance_rate,
                                                            'quantity' => $product->quantity,
                                                            'tax' => $product->tax,
                                                            'type' => $product->payment_details_type,
                                                            'category' => [
                                                                "id" => $product?->productCategory?->id,
                                                                "name" => $product?->productCategory?->name,
                                                                "defaultUnit" => $product?->productCategory?->default_unit,
                                                                "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                                                            ]
                                                        ];
                                                    }),
                                                    'productCategory' => ProductCategory::where("id", $child->product_category_id)?->first(),
                                                    'offerId' => $child->sale_offer_id,
                                                ];
                                            })
                                        ];
                                    }),
                                    'optionalComponents' => $contract_field->offerModel->optionalComponents->map(function ($component) use ($contract_field) {
                                        $productsGroupedByCategory = collect();
                                        if ($contract_field->offerModel->grouped_by == "category" && $component->type == "service") {
                                            $productsGroupedByCategory = Product::with('productCategory')->whereIn('id', $component?->products)->get();
                                        }
                                        return [
                                            'id' => $component->id,
                                            'type' => $component->type,
                                            'product' => [
                                                'id' => $component->product->id,
                                                'articleNumber' => $component->product->article_number,
                                                'name' => $component->product->name,
                                                'listingPrice' => $component->product->listing_price,
                                                'status' => $component->product->status ? 'active' : 'deactive',
                                                'salePrice' => $component->product->sale_price,
                                                'profit' => $component->product->profit,
                                                'discount' => $component->product->discount ?? 0,
                                                'maintenancePrice' => $component->product->maintanence_price,
                                                'maintenanceRate' => $component->product->maintenance_rate,
                                                'quantity' => $component->product->quantity,
                                                'pricePerPeriod' => $component->product->price_per_period,
                                                'paymentPeriod' => $component->product->paymentPeriod,
                                                'productSoftware' => $component->product->productSoftware,
                                                'tax' => $component->product->tax,
                                                'type' => $component->product->payment_details_type,
                                                'unit' => $component->product->productUnit,
                                                'description' => $component->product->description,
                                                'dailyRate' => $component->product->daily_rate,
                                                'serviceDays' => $component->product->service_days,
                                                'category' => [
                                                    "id" => $component->product?->productCategory?->id,
                                                    "name" => $component->product?->productCategory?->name,
                                                    "defaultUnit" => $component->product?->productCategory?->default_unit,
                                                    "isDefaultOnOffers" => $component->product?->productCategory?->is_default_on_offers
                                                ]
                                            ],
                                            'quantity' => $component->quantity,
                                            'duration' => $component->duration,
                                            'hourlyRate' => $component->hourly_rate,
                                            'pricePerPeriod' => $component->price_per_period,
                                            'salePrice' => $component->sale_price,
                                            'maintenanceRate' => $component->maintenance_rate,
                                            'tax' => $component->tax,
                                            'paymentPeriod' => $component->payment_period,
                                            'discount' => $component->discount ?? 0,
                                            'products' => $productsGroupedByCategory->map(function ($product) {
                                                return [
                                                    'id' => $product->id,
                                                    'articleNumber' => $product->article_number,
                                                    'name' => $product->name,
                                                    'listingPrice' => $product->listing_price,
                                                    'status' => $product->status ? 'active' : 'deactive',
                                                    'salePrice' => $product->sale_price,
                                                    'profit' => $product->profit,
                                                    'hourlyRate' => $product->hourly_rate,
                                                    'pricePerPeriod' => $product->price_per_period,
                                                    'dailyRate' => $product->daily_rate,
                                                    'unit' => $product->productUnit ? [
                                                        'id' => $product->productUnit->id,
                                                        'name' => $product->productUnit->name,
                                                        'shortName' => $product->productUnit->short_name,
                                                    ] : null,
                                                    'unitShortName' => $product->productUnit?->short_name,
                                                    'description' => $product->description,
                                                    'discount' => $product->discount ?? 0,
                                                    'maintenancePrice' => $product->maintanence_price,
                                                    'maintenanceRate' => $product->maintenance_rate,
                                                    'quantity' => $product->quantity,
                                                    'tax' => $product->tax,
                                                    'type' => $product->payment_details_type,
                                                    'category' => [
                                                        "id" => $product?->productCategory?->id,
                                                        "name" => $product?->productCategory?->name,
                                                        "defaultUnit" => $product?->productCategory?->default_unit,
                                                        "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                                                    ]
                                                ];
                                            }),
                                            'productCategory' => ProductCategory::where("id", $component->product_category_id)?->first(),
                                            'slaInfrastructureId' => $component->slaInfrastructure ? [
                                                'id' => $component->slaInfrastructure->id,
                                                'name' => $component->slaInfrastructure->name,
                                                'access' => $component->slaInfrastructure->is_access ? true : false,
                                                'includedUsers' => $component->slaInfrastructure->user_included,
                                                'additionalUser' => $component->slaInfrastructure->additional_user_cost,
                                            ] : null,
                                            'slaServiceTimeId' => $component->slaServiceTime ? [
                                                'id' => $component->slaServiceTime->id,
                                                'name' => $component->slaServiceTime->name,
                                                'days' => $component->slaServiceTime->days,
                                                'from' => $component->slaServiceTime->from,
                                                'to' => $component->slaServiceTime->to,
                                            ] : null,
                                            'slaLevelId' => $component->slaLevel ? [
                                                'id' => $component->slaLevel->id,
                                                'name' => $component->slaLevel->name,
                                                'reactionTimeUrgent' => $component->slaLevel->reaction_time_urgent,
                                                'reactionTimeHigh' => $component->slaLevel->reaction_time_high,
                                                'reactionTimeLow' => $component->slaLevel->reaction_time_low,
                                            ] : null,
                                        ];
                                    }),
                                ] : null;
                            }
                        } else if ($contract_field->contractField->type == 'offer-confirmation') {
                            if ($attachment->add_products_to_customer_licences) {
                                $value = ($contract_field?->contractFieldProducts ?? [])?->map(function ($contract_field_product) {
                                    return $this->productDetail($contract_field_product);
                                });
                            } else {
                                $parent_offer = SaleOffer::find($contract_field->offerConfirmationModel->offer_id);
                                $value = $contract_field->offerConfirmationModel ? [
                                    'id' => $contract_field->offerConfirmationModel->id,
                                    'offerType' => $contract_field->offerConfirmationModel->type,
                                    'offerNumber' => $contract_field->offerConfirmationModel->sale_offer_number,
                                    'coverLetterText' => $contract_field->offerConfirmationModel->cover_letter_text,
                                    'offerDescriptionText' => $contract_field->offerConfirmationModel->offer_description_text,
                                    'company' => $contract_field->offerConfirmationModel->company->id,
                                    'term' => $contract_field->offerConfirmationModel->term->id,
                                    'project' => $contract_field->offerConfirmationModel->project?->id,
                                    'removeFromStatistics' => $contract_field->offerConfirmationModel->remove_from_statistics,
                                    'parentOffer' => $parent_offer ? [
                                        'id' => $parent_offer->id,
                                        'offerNumber' => $parent_offer->sale_offer_number,
                                        'company' => $parent_offer->company->company_name,
                                        'terms' => $parent_offer->term->name,
                                        'receiverType' => $parent_offer->receiver_type,
                                        'project' => $parent_offer->project?->name,
                                        'type' => $parent_offer->offer_type,
                                        'totalNetto' => $parent_offer->components->sum('total_netto')
                                    ] : null,
                                    'type' => $contract_field->offerConfirmationModel->offer_type,
                                    'receiverType' => $contract_field->offerConfirmationModel->receiver_type,
                                    'groupedBy' => $contract_field->offerConfirmationModel->grouped_by,
                                    'template' => $contract_field->offerConfirmationModel->template,
                                    'createdBy' => $contract_field->offerConfirmationModel->created_by,
                                    'expiryDate' => Carbon::parse($contract_field->offerConfirmationModel->expiry_date)->toDateString(),
                                    'plannedStartDate' => Carbon::parse($contract_field->offerConfirmationModel->planned_start_date)->toDateString(),
                                    'paymentTerms' => $contract_field->offerConfirmationModel->payment_terms,
                                    'createdAt' => Carbon::parse($contract_field->offerConfirmationModel->created_at)->toDateString(),
                                    'updatedAt' => Carbon::parse($contract_field->offerConfirmationModel->updated_at)->toDateString(),
                                    'contactPerson' => $contract_field->offerConfirmationModel->contact_person,
                                    'externalOrderNumber' => $contract_field->offerConfirmationModel->external_order_number,
                                    'identifier' => $contract_field->offerConfirmationModel->identifier,
                                    'components' => $contract_field->offerConfirmationModel->components()->whereNull('parent_component_id')->get()->map(function ($component) use ($contract_field) {
                                        $productsGroupedByCategory = collect();
                                        if ($contract_field->offerConfirmationModel->grouped_by == "category" && $component->type == "service") {
                                            // get all products part of this category, even duplicates
                                            foreach (($component?->products ?? []) as $product_id) {
                                                $product = Product::with('productCategory')->findOrFail($product_id);
                                                $productsGroupedByCategory->push($product);
                                            }
                                        }
                                        return [
                                            'id' => $component->id,
                                            'type' => $component->type,
                                            'product' => [
                                                'id' => $component->product->id,
                                                'articleNumber' => $component->product->article_number,
                                                'name' => $component->product->name,
                                                'listingPrice' => $component->product->listing_price,
                                                'status' => $component->product->status ? 'active' : 'deactive',
                                                'salePrice' => $component->product->sale_price,
                                                'profit' => $component->product->profit,
                                                'discount' => $component->product->discount ?? 0,
                                                'maintenancePrice' => $component->product->maintanence_price,
                                                'maintenanceRate' => $component->product->maintenance_rate,
                                                'quantity' => $component->product->quantity,
                                                'pricePerPeriod' => $component->product->price_per_period,
                                                'paymentPeriod' => $component->product->paymentPeriod,
                                                'productSoftware' => $component->product->productSoftware,
                                                'tax' => $component->product->tax,
                                                'type' => $component->product->payment_details_type,
                                                'unit' => $component->product->productUnit,
                                                'description' => $component->product->description,
                                                'dailyRate' => $component->product->daily_rate,
                                                'serviceDays' => $component->product->service_days,
                                                'category' => [
                                                    "id" => $component->product?->productCategory?->id,
                                                    "name" => $component->product?->productCategory?->name,
                                                    "defaultUnit" => $component->product?->productCategory?->default_unit,
                                                    "isDefaultOnOffers" => $component->product?->productCategory?->is_default_on_offers
                                                ]
                                            ],
                                            'quantity' => $component->quantity,
                                            'duration' => $component->duration,
                                            'hourlyRate' => $component->hourly_rate,
                                            'pricePerPeriod' => $component->price_per_period,
                                            'salePrice' => $component->sale_price,
                                            'maintenanceRate' => $component->maintenance_rate,
                                            'tax' => $component->tax,
                                            'paymentPeriod' => $component->payment_period,
                                            'discount' => $component->discount ?? 0,
                                            'additionalDescription' => $component->additional_description,
                                            'products' => $productsGroupedByCategory->map(function ($product) {
                                                return [
                                                    'id' => $product->id,
                                                    'articleNumber' => $product->article_number,
                                                    'name' => $product->name,
                                                    'listingPrice' => $product->listing_price,
                                                    'status' => $product->status ? 'active' : 'deactive',
                                                    'salePrice' => $product->sale_price,
                                                    'profit' => $product->profit,
                                                    'hourlyRate' => $product->hourly_rate,
                                                    'pricePerPeriod' => $product->price_per_period,
                                                    'dailyRate' => $product->daily_rate,
                                                    'unit' => $product->productUnit ? [
                                                        'id' => $product->productUnit->id,
                                                        'name' => $product->productUnit->name,
                                                        'shortName' => $product->productUnit->short_name,
                                                    ] : null,
                                                    'unitShortName' => $product->productUnit?->short_name,
                                                    'description' => $product->description,
                                                    'discount' => $product->discount ?? 0,
                                                    'maintenancePrice' => $product->maintanence_price,
                                                    'maintenanceRate' => $product->maintenance_rate,
                                                    'quantity' => $product->quantity,
                                                    'tax' => $product->tax,
                                                    'type' => $product->payment_details_type,
                                                    'category' => [
                                                        "id" => $product?->productCategory?->id,
                                                        "name" => $product?->productCategory?->name,
                                                        "defaultUnit" => $product?->productCategory?->default_unit,
                                                        "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                                                    ]
                                                ];
                                            }),
                                            'productCategory' => ProductCategory::where("id", $component->product_category_id)?->first(),
                                            'offerId' => $component->sale_offer_id,
                                            'slaInfrastructureId' => $component->slaInfrastructure ? [
                                                'id' => $component->slaInfrastructure->id,
                                                'name' => $component->slaInfrastructure->name,
                                                'access' => $component->slaInfrastructure->is_access ? true : false,
                                                'includedUsers' => $component->slaInfrastructure->user_included,
                                                'additionalUser' => $component->slaInfrastructure->additional_user_cost,
                                            ] : null,
                                            'slaServiceTimeId' => $component->slaServiceTime ? [
                                                'id' => $component->slaServiceTime->id,
                                                'name' => $component->slaServiceTime->name,
                                                'days' => $component->slaServiceTime->days,
                                                'from' => $component->slaServiceTime->from,
                                                'to' => $component->slaServiceTime->to,
                                            ] : null,
                                            'slaLevelId' => $component->slaLevel ? [
                                                'id' => $component->slaLevel->id,
                                                'name' => $component->slaLevel->name,
                                                'reactionTimeUrgent' => $component->slaLevel->reaction_time_urgent,
                                                'reactionTimeHigh' => $component->slaLevel->reaction_time_high,
                                                'reactionTimeLow' => $component->slaLevel->reaction_time_low,
                                            ] : null,
                                            'children' => $component->children->map(function ($child) use ($contract_field) {
                                                $productsGroupedByCategory = collect();
                                                if ($contract_field->offerConfirmationModel->grouped_by == "category" && $child->type == "service") {
                                                    $productsGroupedByCategory = Product::with('productCategory')->whereIn('id', $child?->products)->get();
                                                }
                                                return [
                                                    'id' => $child->id,
                                                    'type' => $child->type,
                                                    'product' => [
                                                        'id' => $child->product->id,
                                                        'articleNumber' => $child->product->article_number,
                                                        'name' => $child->product->name,
                                                        'listingPrice' => $child->product->listing_price,
                                                        'status' => $child->product->status ? 'active' : 'deactive',
                                                        'salePrice' => $child->product->sale_price,
                                                        'profit' => $child->product->profit,
                                                        'discount' => $child->product->discount ?? 0,
                                                        'maintenancePrice' => $child->product->maintanence_price,
                                                        'maintenanceRate' => $child->product->maintenance_rate,
                                                        'quantity' => $child->product->quantity,
                                                        'pricePerPeriod' => $child->product->price_per_period,
                                                        'paymentPeriod' => $child->product->paymentPeriod,
                                                        'productSoftware' => $child->product->productSoftware,
                                                        'tax' => $child->product->tax,
                                                        'type' => $child->product->payment_details_type,
                                                        'unit' => $child->product->productUnit ? [
                                                            'id' => $child->product->productUnit->id,
                                                            'name' => $child->product->productUnit->name,
                                                            'shortName' => $child->product->productUnit->short_name,
                                                        ] : null,
                                                        'description' => $child->product->description,
                                                        'dailyRate' => $child->product->daily_rate,
                                                        'serviceDays' => $child->product->service_days,
                                                        'category' => [
                                                            "id" => $child->product?->productCategory?->id,
                                                            "name" => $child->product?->productCategory?->name,
                                                            "defaultUnit" => $child->product?->productCategory?->default_unit,
                                                            "isDefaultOnOffers" => $child->product?->productCategory?->is_default_on_offers
                                                        ]
                                                    ],
                                                    'quantity' => $child->quantity,
                                                    'duration' => $child->duration,
                                                    'hourlyRate' => $child->hourly_rate,
                                                    'pricePerPeriod' => $child->price_per_period,
                                                    'salePrice' => $child->sale_price,
                                                    'maintenanceRate' => $child->maintenance_rate,
                                                    'tax' => $child->tax,
                                                    'paymentPeriod' => $child->payment_period,
                                                    'discount' => $child->discount ?? 0,
                                                    'products' => $productsGroupedByCategory->map(function ($product) {
                                                        return [
                                                            'id' => $product->id,
                                                            'articleNumber' => $product->article_number,
                                                            'name' => $product->name,
                                                            'listingPrice' => $product->listing_price,
                                                            'status' => $product->status ? 'active' : 'deactive',
                                                            'salePrice' => $product->sale_price,
                                                            'profit' => $product->profit,
                                                            'hourlyRate' => $product->hourly_rate,
                                                            'pricePerPeriod' => $product->price_per_period,
                                                            'dailyRate' => $product->daily_rate,
                                                            'unit' => $product->productUnit ? [
                                                                'id' => $product->productUnit->id,
                                                                'name' => $product->productUnit->name,
                                                                'shortName' => $product->productUnit->short_name,
                                                            ] : null,
                                                            'unitShortName' => $product->productUnit?->short_name,
                                                            'description' => $product->description,
                                                            'discount' => $product->discount ?? 0,
                                                            'maintenancePrice' => $product->maintanence_price,
                                                            'maintenanceRate' => $product->maintenance_rate,
                                                            'quantity' => $product->quantity,
                                                            'tax' => $product->tax,
                                                            'type' => $product->payment_details_type,
                                                            'category' => [
                                                                "id" => $product?->productCategory?->id,
                                                                "name" => $product?->productCategory?->name,
                                                                "defaultUnit" => $product?->productCategory?->default_unit,
                                                                "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                                                            ]
                                                        ];
                                                    }),
                                                    'productCategory' => ProductCategory::where("id", $child->product_category_id)?->first(),
                                                    'offerId' => $child->sale_offer_id,
                                                ];
                                            })
                                        ];
                                    }),
                                    'optionalComponents' => $contract_field->offerConfirmationModel->optionalComponents->map(function ($component) use ($contract_field) {
                                        $productsGroupedByCategory = collect();
                                        if ($contract_field->offerConfirmationModel->grouped_by == "category" && $component->type == "service") {
                                            $productsGroupedByCategory = Product::with('productCategory')->whereIn('id', $component?->products)->get();
                                        }
                                        return [
                                            'id' => $component->id,
                                            'type' => $component->type,
                                            'product' => [
                                                'id' => $component->product->id,
                                                'articleNumber' => $component->product->article_number,
                                                'name' => $component->product->name,
                                                'listingPrice' => $component->product->listing_price,
                                                'status' => $component->product->status ? 'active' : 'deactive',
                                                'salePrice' => $component->product->sale_price,
                                                'profit' => $component->product->profit,
                                                'discount' => $component->product->discount ?? 0,
                                                'maintenancePrice' => $component->product->maintanence_price,
                                                'maintenanceRate' => $component->product->maintenance_rate,
                                                'quantity' => $component->product->quantity,
                                                'pricePerPeriod' => $component->product->price_per_period,
                                                'paymentPeriod' => $component->product->paymentPeriod,
                                                'productSoftware' => $component->product->productSoftware,
                                                'tax' => $component->product->tax,
                                                'type' => $component->product->payment_details_type,
                                                'unit' => $component->product->productUnit,
                                                'description' => $component->product->description,
                                                'dailyRate' => $component->product->daily_rate,
                                                'serviceDays' => $component->product->service_days,
                                                'category' => [
                                                    "id" => $component->product?->productCategory?->id,
                                                    "name" => $component->product?->productCategory?->name,
                                                    "defaultUnit" => $component->product?->productCategory?->default_unit,
                                                    "isDefaultOnOffers" => $component->product?->productCategory?->is_default_on_offers
                                                ]
                                            ],
                                            'quantity' => $component->quantity,
                                            'duration' => $component->duration,
                                            'hourlyRate' => $component->hourly_rate,
                                            'pricePerPeriod' => $component->price_per_period,
                                            'salePrice' => $component->sale_price,
                                            'maintenanceRate' => $component->maintenance_rate,
                                            'tax' => $component->tax,
                                            'paymentPeriod' => $component->payment_period,
                                            'discount' => $component->discount ?? 0,
                                            'products' => $productsGroupedByCategory->map(function ($product) {
                                                return [
                                                    'id' => $product->id,
                                                    'articleNumber' => $product->article_number,
                                                    'name' => $product->name,
                                                    'listingPrice' => $product->listing_price,
                                                    'status' => $product->status ? 'active' : 'deactive',
                                                    'salePrice' => $product->sale_price,
                                                    'profit' => $product->profit,
                                                    'hourlyRate' => $product->hourly_rate,
                                                    'pricePerPeriod' => $product->price_per_period,
                                                    'dailyRate' => $product->daily_rate,
                                                    'unit' => $product->productUnit ? [
                                                        'id' => $product->productUnit->id,
                                                        'name' => $product->productUnit->name,
                                                        'shortName' => $product->productUnit->short_name,
                                                    ] : null,
                                                    'unitShortName' => $product->productUnit?->short_name,
                                                    'description' => $product->description,
                                                    'discount' => $product->discount ?? 0,
                                                    'maintenancePrice' => $product->maintanence_price,
                                                    'maintenanceRate' => $product->maintenance_rate,
                                                    'quantity' => $product->quantity,
                                                    'tax' => $product->tax,
                                                    'type' => $product->payment_details_type,
                                                    'category' => [
                                                        "id" => $product?->productCategory?->id,
                                                        "name" => $product?->productCategory?->name,
                                                        "defaultUnit" => $product?->productCategory?->default_unit,
                                                        "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                                                    ]
                                                ];
                                            }),
                                            'productCategory' => ProductCategory::where("id", $component->product_category_id)?->first(),
                                            'slaInfrastructureId' => $component->slaInfrastructure ? [
                                                'id' => $component->slaInfrastructure->id,
                                                'name' => $component->slaInfrastructure->name,
                                                'access' => $component->slaInfrastructure->is_access ? true : false,
                                                'includedUsers' => $component->slaInfrastructure->user_included,
                                                'additionalUser' => $component->slaInfrastructure->additional_user_cost,
                                            ] : null,
                                            'slaServiceTimeId' => $component->slaServiceTime ? [
                                                'id' => $component->slaServiceTime->id,
                                                'name' => $component->slaServiceTime->name,
                                                'days' => $component->slaServiceTime->days,
                                                'from' => $component->slaServiceTime->from,
                                                'to' => $component->slaServiceTime->to,
                                            ] : null,
                                            'slaLevelId' => $component->slaLevel ? [
                                                'id' => $component->slaLevel->id,
                                                'name' => $component->slaLevel->name,
                                                'reactionTimeUrgent' => $component->slaLevel->reaction_time_urgent,
                                                'reactionTimeHigh' => $component->slaLevel->reaction_time_high,
                                                'reactionTimeLow' => $component->slaLevel->reaction_time_low,
                                            ] : null,
                                        ];
                                    }),
                                ] : null;
                            }
                        } else if ($contract_field->contractField->type == 'products') {
                            $value = ($contract_field?->contractFieldProducts ?? [])?->map(function ($contract_field_product) {
                                return $this->productDetail($contract_field_product);
                            });
                        }
                        return [
                            'id' => $contract_field->contractField->id,
                            'key' => $contract_field->contractField->key,
                            'type' => $contract_field->contractField->type,
                            'value' => $value,
                            'createdAt' => Carbon::parse($contract_field->contractField->createdAt)->toDateString()
                        ];
                    }),
                    'slaInfrastructureId' => $attachment->pivot->slaInfrastructure ? [
                        'id' => $attachment->pivot->slaInfrastructure->id,
                        'name' => $attachment->pivot->slaInfrastructure->name,
                        'category' => $attachment->pivot->slaInfrastructure->category
                    ] : null,
                    'slaInfrastructureUserSupport' => $attachment->pivot->sla_infrastructure_user_support,
                    'slaServiceTimeId' => $attachment->pivot->slaServiceTime ? [
                        'id' => $attachment->pivot->slaServiceTime->id,
                        'name' => $attachment->pivot->slaServiceTime->name,
                    ] : null,
                    'slaLevelId' => $attachment->pivot->slaLevel ? [
                        'id' => $attachment->pivot->slaLevel->id,
                        'name' => $attachment->pivot->slaLevel->name,
                        'reactionTimeUrgent' => $attachment->pivot->slaLevel->reaction_time_urgent,
                        'reactionTimeHigh' => $attachment->pivot->slaLevel->reaction_time_high,
                        'reactionTimeLow' => $attachment->pivot->slaLevel->reaction_time_low,
                    ] : null,
                    'userId' => $attachment->pivot->user_id,
                    'isFixedPrice' => $attachment->pivot?->is_fixed_price,
                    'hourlyRate' => $attachment->pivot?->hourly_rate,
                    'hoursPerYear' => $attachment->pivot?->hours_per_year,
                    'totalPrice' => $attachment->pivot?->total_price,
                    'products' => ($attachment->pivot?->attachmentProducts ?? [])?->map(function ($attachmentProduct) use (&$price_per_month) {
                        $price_per_month += $attachmentProduct?->total_netto ?? 0;
                        $payment_period = PaymentPeriod::find($attachmentProduct->payment_period);
                        return [
                            'id' => $attachmentProduct->product?->id,
                            'attachmentProductId' => $attachmentProduct->id,
                            'attachmentProductType' => $attachmentProduct->type,
                            'contractAttachmentId' => $attachmentProduct->contract_attachment_id,
                            'articleNumber' => $attachmentProduct->product?->article_number,
                            'name' => $attachmentProduct->product_name ?? $attachmentProduct->product?->name,
                            'isProductNameEdit' => $attachmentProduct->product?->is_product_name_edit,
                            'listingPrice' => $attachmentProduct->product?->listing_price,
                            'status' => $attachmentProduct->product?->status ? 'active' : 'deactive',
                            'salePrice' => $attachmentProduct->sale_price,
                            'profit' => $attachmentProduct->product?->profit,
                            'discount' => $attachmentProduct->discount ?? 0,
                            'maintenancePrice' => $attachmentProduct->product?->maintanence_price,
                            'maintenanceRate' => $attachmentProduct->product?->maintenance_rate,
                            'quantity' => $attachmentProduct->quantity,
                            'serviceHours' => $attachmentProduct->service_hours,
                            'pricePerPeriod' => $attachmentProduct->price_per_period,
                            'paymentPeriod' => $payment_period ? [
                                'id' => $payment_period->id,
                                'name' => $payment_period->name,
                                'billingCycle' => $payment_period->billing_cycle,
                                'createdAt' => Carbon::parse($payment_period->created_at)->format('d/m/y')
                            ] : null,
                            'productSoftware' => $attachmentProduct->productSoftware,
                            'tax' => $attachmentProduct->tax,
                            'type' => $attachmentProduct->product?->payment_details_type,
                            'unit' => $attachmentProduct->product?->productUnit,
                            'description' => $attachmentProduct->product?->description,
                            'dailyRate' => $attachmentProduct->product?->daily_rate,
                            'serviceDays' => $attachmentProduct->product?->service_days,
                            'category' => [
                                "id" => $attachmentProduct->product?->productCategory?->id,
                                "name" => $attachmentProduct->product?->productCategory?->name,
                                "defaultUnit" => $attachmentProduct->product?->productCategory?->default_unit,
                                "isDefaultOnOffers" => $attachmentProduct->product?->productCategory?->is_default_on_offers,
                                'productType' => $attachmentProduct->product?->productCategory?->product_type,
                                'serviceContingent' => $attachmentProduct->product?->productCategory?->service_contingent,
                            ],
                            'hourlyRate' => $attachmentProduct->hourly_rate,
                            'totalNetto' => $attachmentProduct->total_netto,
                            'duration' => $attachmentProduct->duration,
                            'additionalDescription' => $attachmentProduct->additional_description,
                            'slaServiceTimeId' => $attachmentProduct->slaServiceTime ? [
                                'id' => $attachmentProduct->slaServiceTime->id,
                                'name' => $attachmentProduct->slaServiceTime->name,
                                'days' => $attachmentProduct->slaServiceTime->days,
                                'from' => $attachmentProduct->slaServiceTime->from,
                                'to' => $attachmentProduct->slaServiceTime->to,
                            ] : null,
                            'slaLevelId' => $attachmentProduct->slaLevel ? [
                                'id' => $attachmentProduct->slaLevel->id,
                                'name' => $attachmentProduct->slaLevel->name,
                                'reactionTimeUrgent' => $attachmentProduct->slaLevel->reaction_time_urgent,
                                'reactionTimeHigh' => $attachmentProduct->slaLevel->reaction_time_high,
                                'reactionTimeLow' => $attachmentProduct->slaLevel->reaction_time_low,
                            ] : null,
                        ];
                    }),
                    'startDate' => $attachment->pivot?->start_date ? Carbon::parse($attachment->pivot?->start_date)->toDateString() : null,
                    'terminationDate' => $attachment->pivot?->termination_date ? Carbon::parse($attachment->pivot?->termination_date)->toDateString() : null,
                    'signedDate' => $attachment->pivot?->signed_date ? Carbon::parse($attachment->pivot?->signed_date)->toDateString() : null,
                    'createdAt' => Carbon::parse($attachment->pivot?->created_at)->toDateString(),
                    'pricePerMonth' => $price_per_month
                ];
            }),
        ];
    }


    private function productDetail($contract_field_product)
    {
        $payment_period = PaymentPeriod::find($contract_field_product->payment_period);
        return [
            'id' => $contract_field_product->product?->id,
            'contractFieldProductId' => $contract_field_product->id,
            'contractFieldId' => $contract_field_product->contract_field_id,
            'articleNumber' => $contract_field_product->product?->article_number,
            'type' => $contract_field_product->type,
            'name' => $contract_field_product->product_name ?? $contract_field_product->product?->name,
            'listingPrice' => $contract_field_product->product?->listing_price,
            'status' => $contract_field_product->product?->status ? 'active' : 'deactive',
            'salePrice' => $contract_field_product->sale_price,
            'profit' => $contract_field_product->product?->profit,
            'discount' => $contract_field_product->discount ?? 0,
            'maintenancePrice' => $contract_field_product->maintenance_price,
            'maintenanceRate' => $contract_field_product->maintenance_rate,
            'quantity' => $contract_field_product->quantity,
            'serviceHours' => $contract_field_product->service_hours,
            'pricePerPeriod' => $contract_field_product->price_per_period,
            'isProductNameEdit' => $contract_field_product->product?->is_product_name_edit,
            'paymentPeriod' => $payment_period ? [
                'id' => $payment_period->id,
                'name' => $payment_period->name,
                'billingCycle' => $payment_period->billing_cycle,
                'createdAt' => Carbon::parse($payment_period->created_at)->format('d/m/y')
            ] : null,
            'productSoftware' => $contract_field_product->productSoftware,
            'tax' => $contract_field_product->tax,
            'paymentDetailType' => $contract_field_product->product?->payment_details_type,
            'unit' => $contract_field_product->product?->productUnit,
            'description' => $contract_field_product->product?->description,
            'dailyRate' => $contract_field_product->product?->daily_rate,
            'serviceDays' => $contract_field_product->product?->service_days,
            'category' => [
                "id" => $contract_field_product->product?->productCategory?->id,
                "name" => $contract_field_product->product?->productCategory?->name,
                "defaultUnit" => $contract_field_product->product?->productCategory?->default_unit,
                "isDefaultOnOffers" => $contract_field_product->product?->productCategory?->is_default_on_offers,
                'productType' => $contract_field_product->product?->productCategory?->product_type,
                'serviceContingent' => $contract_field_product->product?->productCategory?->service_contingent,
            ],
            'hourlyRate' => $contract_field_product->hourly_rate,
            'totalNetto' => $contract_field_product->total_netto,
            'duration' => $contract_field_product->duration,
            'additionalDescription' => $contract_field_product->additional_description,
            'slaServiceTimeId' => $contract_field_product->slaServiceTime ? [
                'id' => $contract_field_product->slaServiceTime->id,
                'name' => $contract_field_product->slaServiceTime->name,
                'days' => $contract_field_product->slaServiceTime->days,
                'from' => $contract_field_product->slaServiceTime->from,
                'to' => $contract_field_product->slaServiceTime->to,
            ] : null,
            'slaLevelId' => $contract_field_product->slaLevel ? [
                'id' => $contract_field_product->slaLevel->id,
                'name' => $contract_field_product->slaLevel->name,
                'reactionTimeUrgent' => $contract_field_product->slaLevel->reaction_time_urgent,
                'reactionTimeHigh' => $contract_field_product->slaLevel->reaction_time_high,
                'reactionTimeLow' => $contract_field_product->slaLevel->reaction_time_low,
            ] : null,
        ];
    }
}
