<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class WorkshopTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "templateName" => $this->template_name,
            "templateVersion" => $this->template_version,
            "status" => $this->status,
            "consultant" => $this->consultant ? [
                'id' => $this->consultant->id,
                'employee' => $this->consultant->first_name . ' ' . $this->consultant->last_name,
                'firstName' => $this->consultant->first_name,
                'lastName' => $this->consultant->last_name,
                'email' => $this->consultant->email,
                'userId' => $this->consultant->user_id,
                'dateOfBirth' => $this->consultant->date_of_birth,
                'teams' => [],
                'departments' => '',
                "personalNumber" => $this->consultant->jobData->personal_number ?? '',
                "jobTitle" => $this->consultant->jobData->job_title ?? '',
                "joinDate" => $this->consultant->jobData->join_date ?? '',
                "location" => $this->consultant->jobData->location?->name ?? '',
                "exitDate" => $this->consultant->jobData->exit_date ?? '',
                "grossSalary" => $this->consultant->userCompensationData->gross_monthly_salary ?? '',
                "profilePicUrl" => ""
            ] : "",
            "assignedProducts" => $this?->products?->map(function ($product) {
                return  [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'articleNumber' => $product->article_number,
                    'salePrice' => floatval($product->sale_price),
                    'discount' => floatval($product->discount),
                    'status' => $product->status ? 'active' : 'deactive',
                    'listingPrice' => $product->listing_price,
                    'profit' => $product->profit,
                    'quantity' => 1,
                    'totalPrice' => floatval($product->total_price),
                    'tax' => +$product->tax,
                    'maintenancePrice' => $product->maintanence_price,
                    'maintenanceRate' => floatval($product->maintenance_rate),
                    'productSoftware' => $product->productSoftware,
                    'unit' => $product->productUnit,
                    'type' => $product->payment_details_type,
                    'dailyRate' => $product->daily_rate,
                    'serviceDays' => $product->service_days,
                    'paymentPeriod' => $product->payment_period,
                    'category' => [
                        "id" => $product?->productCategory?->id,
                        "name" => $product?->productCategory?->name,
                        "defaultUnit" => $product?->productCategory?->default_unit,
                        "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                    ]
                ];
            }),
            "cards" => ($this?->cards ?? [])->map(function ($card) {
                return [
                    "id" => $card->id,
                    "type" => "card",
                    "workshopTemplateId" => $card->workshop_template_id,
                    "configuration" => $card->configuration,
                    "rows" => ($card?->rows ?? [])->map(function ($row) {
                        return [
                            "id" => $row->id,
                            "type" => "row",
                            "workshopTemplatesCardId" => $row->workshop_templates_card_id,
                            "configuration" => $row->configuration,
                            "columns" => ($row?->columns ?? [])->map(function ($column) {
                                return [
                                    "id" => $column->id,
                                    "type" => "column",
                                    "workshopTemplatesRowId" => $column->workshop_templates_row_id,
                                    "configuration" => $column->configuration,
                                    "widgets" => ($column?->widgets ?? [])->map(function ($widget) {
                                        return [
                                            "id" => $widget->id,
                                            "type" => $widget->type,
                                            "workshopTemplatesColumnId" => $widget->workshop_templates_column_id,
                                            "configuration" => $widget->configuration,
                                        ];
                                    })
                                ];
                            })
                        ];
                    })
                ];
            }),
            "createdAt" => Carbon::parse($this->createdAt)->toDateString(),
            'files' => $this?->files?->map(fn ($file) => [
                'id' => $file->id,
                'storage_name' => $file->storage_name,
                'name' => $file->viewable_name,
                'size' => $file->storage_size ?? null,
            ]) ?? [],
        ];
    }
}
