<?php

namespace App\Helpers;

use App\Http\Resources\ModuleHistoryResource;
use App\Models\GlobalSetting;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SaleOffer;
use App\Models\SaleOfferComponent;
use App\Models\SaleOfferOptionalComponent;
use App\Models\Invoice;
use App\Models\ProductInvoiceCategory;
use App\Models\UserProfileData;
use App\Models\PaymentPeriod;
use Carbon\Carbon;
use Exception;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderAndOfferHelper
{

    public static function index($request)
    {
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new SaleOffer();
        if ($sort_by && $sort_order) {
            $model = self::applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $per_page = $request->perPage ?? 25;
        $offerType = $request->offerType ?? 'offer';
        $model = $model->where('sale_offers.type', $offerType)->when($request->customerId, function ($query) use ($request) {
            $query->where('company_id', $request->customerId ?? null);
        });

        if ($request->search) {
            $model->where(function ($query) use ($request) {
                $query->orWhere('sale_offer_number', 'LIKE', '%' . $request->search . '%');
                $query->orWhere('receiver_type', 'LIKE', '%' . $request->search . '%');
                $query->orWhere('offer_type', 'LIKE', '%' . $request->search . '%');
                $query->orWhere('netto_total', 'LIKE', '%' . $request->search . '%');
                $query->orWhere('offer_status', 'LIKE', '%' . $request->search . '%');
                $query->orWhere('identifier', 'LIKE', '%' . $request->search . '%');
                $query->orWhereHas('project', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                });
                $query->orWhereHas('company', function ($query) use ($request) {
                    $query->where('company_name', 'LIKE', '%' . $request->search . '%');
                });
                $query->orWhereHas('term', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                });
            });
        }
        if ($request->type) {
            $model->where('offer_type', '=', $request->type);
        }

        if ($request->offerStatus) {
            $model->where('offer_status', '=', $request->offerStatus);
        }

        if ($request->orderStatus) {
            $model->where('order_status', '=', $request->orderStatus);
        }


        if ($offerType == 'offer' && $request->forCustomerPortal) {
            $model->where('offer_status', '!=', 'entwurf');
        }

        $models = $model->paginate($per_page);

        $model_collect = $models->map(function ($item) use ($offerType) {
            $returnData = [
                'id' => $item->id,
                'offerNumber' => $item->sale_offer_number,
                'company' => $item->company->company_name ?? null,
                'terms' => $item->term->name,
                'receiverType' => $item->receiver_type,
                'project' => $item->project?->name,
                'createdAt' => Carbon::parse($item->created_at)->toDateString(),
                'type' => $item->offer_type,
                'offerStatus' => $item->offer_status,
                'totalNetto' => floatval($item->netto_total),
                'identifier' => $item->identifier,
                'companyDetails' => $item->company ? [
                    'id' => $item->company?->id,
                    'companyName' => $item->company?->company_name,
                    'vatId' => $item->company?->vat_id,
                    'url' => $item->company?->url,
                    'type' => $item->company?->type,
                    'customerType' => $item->company?->customer_type,
                    'companyNumber' => $item->company?->company_number,
                    'state' => $item->company?->headQuarters?->state,
                    'city' => $item->company?->headQuarters?->city,
                    'country' => $item->company?->headQuarters?->country,
                    'address' => $item->company?->headQuarters?->address_first,
                    'code' => $item->company?->headQuarters?->zip,
                    'notes' => $item->company?->notes,
                    'status' => $item->company?->status,
                    'expiryDate' => $item->company?->expiry_dt ? Carbon::parse($item->company?->expiry_dt)->toDateString() : '',
                    'termsOfPayment' => $item->company?->terms_of_payment,
                ] : null,
            ];
            if ($offerType == 'order') {
                $returnData['orderStatus'] = $item->order_status;
            } else {
                $returnData['offerStatus'] = $item->offer_status;
            }
            return $returnData;
        });

        return response()->json([
            'data' => $model_collect, 'links' => $models->links(),
            'meta' => [
                'current_page' => $models->currentPage(),
                'from' => $models->firstItem(),
                'last_page' => $models->lastPage(),
                'path' => request()->url(),
                'per_page' => $models->perPage(),
                'to' => $models->lastItem(),
                'total' => $models->total(),
            ],
        ], 200);
    }


    public static function applySortingBeforePagination($model, $sortBy, $sortOrder)
    {
        $sortByParts = explode('.', $sortBy);

        if (count($sortByParts) > 1) {
            // Handle sorting with nested relationships
            $nestedColumn = Str::snake(array_pop($sortByParts));
            $nestedRelationship = implode('.', $sortByParts);             //not possible without the use of joins
            $relatedModel = "App\Models\\" . $nestedRelationship;

            $modelInstance = app($relatedModel);
            $foreignKeyName = $model->{$nestedRelationship}()->getForeignKeyName();
            $model = $model::leftJoin($modelInstance->getTable(), "{$model->getTable()}.{$foreignKeyName}", "=", "{$modelInstance->getTable()}.id")
                ->select("{$modelInstance->getTable()}.*", "{$model->getTable()}.*")
                ->orderBy("{$modelInstance->getTable()}.{$nestedColumn}", $sortOrder);
        } else {
            $sortByParts = explode('-', $sortBy);
            if (count($sortByParts) > 1) {

                // Handle sorting with a date range
                $startColumn = Str::snake($sortByParts[0]);
                $endColumn = Str::snake($sortByParts[1]);

                $model = $model->orderBy($startColumn, $sortOrder)
                    ->orderBy($endColumn, $sortOrder);
            } else {
                // Handle sorting with a single column
                $columnName = Str::snake($sortByParts[0]);
                if (strpos($columnName, '_numeric') !== false) {
                    // Remove "_numeric" from the column name for sorting
                    $numericColumnName = str_replace('_numeric', '', $columnName);
                    $model = $model->orderByRaw('CAST(' . $numericColumnName . ' AS SIGNED) ' . $sortOrder);
                } else {
                    $model = $model->orderBy($columnName, $sortOrder);
                }
            }
        }

        return $model;
    }

    public static function store($request)
    {
        $model = new SaleOffer();
        $global_offer_setting = GlobalSetting::where('key', $request->offerType ?? 'offer')
            ->where('year', date('Y'))->first();
        if (empty($global_offer_setting)) {
            $global_setting = new GlobalSetting();
            $global_setting->key = $request->offerType ?? 'offer';
            $global_setting->value = ($request->offerType ?? 'offer') == 'order' ? '1' : 76001;
            $global_setting->year = date('Y');
            $global_setting->save();
        } else {
            $global_setting = tap(DB::table('global_settings')->where('key', $request->offerType ?? 'offer')->where('year', date('Y')))->update([
                'value' => DB::raw('value+1')
            ])->first();
        }
        $model->sale_offer_number = ($request->offerType ?? 'offer') == 'order' ? ('AB' . date('Y') . str_pad($global_setting->value, 4, '0', STR_PAD_LEFT)) : (date('Y') . '-' . $global_setting->value);
        try {
            self::saveData($model, $request, 'create' . ucfirst($request->offerType ?? 'offer'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json(['message' => 'Record created successfully.'], 200);
    }

    private static function saveData($model, $request, $action)
    {


        DB::transaction(function () use ($model, $request, $action) {
            $model->remove_from_statistics = $request->removeFromStatistics ?? 0;
            // if the order_status is other than draft then only update the status of the offer and return
            if ($model->order_status != 'draft' && $model->order_status) {
                $model->order_status = $request->orderStatus;
                // if status is not 'sent' then update the cover letter text and offer description text
                if ($model->order_status != 'sent') {
                    $model->cover_letter_text = $request->coverLetterText;
                    $model->offer_description_text = $request->offerDescriptionText;
                }
                $model->save();
                return;
            }
            $model->type = $request->offerType ?? 'offer';
            $model->receiver_type = $request->receiverType;
            $model->cover_letter_text = $request->coverLetterText;
            $model->offer_description_text = $request->offerDescriptionText;
            $model->offer_type = $request->type;
            $model->company_id = $request->companyId;
            $model->term_id = $request->termId;
            $model->project_id = $request->projectId;
            $model->grouped_by = $request->groupedBy;
            $model->template = $request->template;
            $model->created_by = $request->createdBy;
            if ($model->type == 'order') {
                $model->external_order_number = $request->externalOrderNumber;
            }
            $model->expiry_date = Carbon::parse($request->expiryDate);
            if ($request->offerType == 'order') {
                $model->planned_start_date = $request->plannedStartDate ? Carbon::parse($request->plannedStartDate) : Carbon::now();
            }
            $model->payment_terms = $request->paymentTerms;
            $model->contact_person = $request->contactPerson;
            $model->order_status = $request->orderStatus;
            $model->identifier = $request->identifier;
            $model->is_visible_for_customer = $request->isVisibleForCustomer ?? false;
            // update the offer_status if the 'offerType' in $request is set to 'offer'
            if ($action == 'createOffer' || $action == 'updateOffer') {
                $model->offer_status = $request->offerStatus;
            }
            // if the request has an 'offerId' then it means that the offer being created is an 'offer confirmation', for which
            // the 'offerId' must be set
            if (isset($request->offerId)) {
                $model->offer_id = $request->offerId;
            }
            // if the order is being updated and the order_status has been modified and the 'order_status' is set to approved
            // then we must set the 'offer_status' of the parent offer to 'beauftragt'
            if ($action == 'updateOrder' && $model->isDirty('order_status') && ($model->order_status == 'approved' || $model->order_status == 'sent')) {
                $parent_offer = SaleOffer::findOrFail($model->offer_id);
                $parent_offer->offer_status = 'beauftragt';
                $parent_offer->save();
            }
            $model->save();

            $component_ids = [];
            foreach ($request->types as $type) {
                $component = SaleOfferComponent::firstOrNew(['id' => isset($type['id']) ? $type['id'] : null]);
                $component->type = $type['type'];
                $component->product_id = isset($type['productId']) ? $type['productId'] : null;
                if ($type['type'] === 'service') {

                    if ($request->groupedBy == 'nothing') {
                        $component->product_name = isset($type['name']) ? $type['name'] : null;
                    } else if ($request->groupedBy == 'category') {
                        $productNameArr = [];
                        foreach ($type['productFullDetails'] as $key => $value) {
                            $productNameArr[] = $value['name'];
                        }

                        $component->product_name = isset($productNameArr) ? $productNameArr : null;
                    }
                } else {
                    $component->product_name = isset($type['productFullDetails']['name']) ? $type['productFullDetails']['name'] : null;
                }

                $component->quantity = $type['quantity'];
                $component->hourly_rate = isset($type['hourlyRate']) ? $type['hourlyRate'] : 0;

                $component->price_per_period = isset($type['pricePerPeriod']) ? $type['pricePerPeriod'] : null;
                $component->payment_period = isset($type['paymentPeriod']['id']) ? $type['paymentPeriod']['id'] : null;
                $component->tax = isset($type['tax']) ? $type['tax'] : null;
                $component->duration = isset($type['duration']) ? $type['duration'] : 1;
                $component->discount = $type['discount'] ?? 0;
                $component->additional_description = isset($type['additionalDescription']) ? $type['additionalDescription'] : null;
                $component->maintenance_rate = isset($type['maintenanceRate']) ? $type['maintenanceRate'] : null;
                $component->sale_price = isset($type['salePrice']) ? $type['salePrice'] : null;
                $component->products = isset($type['products']) ? $type['products'] : null;
                $component->product_category_id = isset($type['productCategoryId']) ? $type['productCategoryId'] : null;
                $component->sale_offer_id = $model->id;
                if ($type['type'] == 'license') {
                    $component->total_netto = $type['productFullDetails']['priceWithoutTax'];
                } elseif ($type['type'] == 'maintenance') {
                    $component->total_netto = $type['productFullDetails']['maintenancePrice'];
                    $component->total_amount = $type['productFullDetails']['maintenancePrice'] + $type['productFullDetails']['priceWithoutTax'];
                } elseif ($type['type'] == 'service' && $request->groupedBy == 'nothing') {
                    $totalNetto = ($type['hourlyRate'] * $type['quantity']) - (($type['hourlyRate'] * $type['quantity'] * ($type['discount'] ?? 0)) / 100);
                    $component->total_netto = $totalNetto;
                } elseif ($type['type'] == 'service' && $request->groupedBy == 'category') {
                    $component->total_netto = $type['nettoTotal'];
                } else {
                    $component->total_netto = $type['productFullDetails']['nettoTotal'];
                }

                // save the SLA values only when the type is 'application'
                if ($type['type'] == 'application') {
                    $component->sla_infrastructure_id = isset($type['productFullDetails']['slaInfrastructureId']) ? $type['productFullDetails']['slaInfrastructureId'] : null;
                    $component->sla_service_time_id = isset($type['productFullDetails']['slaServiceTimeId']) ? $type['productFullDetails']['slaServiceTimeId'] : null;
                    $component->sla_level_id = isset($type['productFullDetails']['slaLevelId']) ? $type['productFullDetails']['slaLevelId'] : null;
                }

                $component->save();

                /*    if ($component->type == 'license' || $component->type == 'cloud') {

                    $component->license()->updateOrCreate(['sale_offer_component_id' => $component->id], [
                        'company_id' => $model->company_id,
                        'product_id' => $component->product_id,
                        'sale_offer_component_id' => $component->id,
                        'quantity' => $component->quantity,
                        'sale_price' => $component->sale_price,
                        'maintenance_price' => $component->maintenance_rate,
                    ]);
                }  */
                array_push($component_ids, $component->id);

                if (isset($type['children'])) {
                    foreach ($type['children'] as $child) {
                        $child_component = SaleOfferComponent::firstOrNew(['id' => isset($child['id']) ? $child['id'] : null]);
                        $child_component->type = $type['type'];
                        $child_component->product_id = isset($child['productId']) ? $child['productId'] : null;
                        $child_component->quantity = $child['quantity'];
                        if ($type['type'] === 'service') {
                            $child_component->product_name = isset($child['name']) ? $child['name'] : null;
                        } else {
                            $child_component->product_name = isset($child['name']) ? $child['name'] : null;
                        }

                        $child_component->price_per_period = isset($child['pricePerPeriod']) ? $child['pricePerPeriod'] : null;
                        $child_component->duration = isset($child['duration']) ? $child['duration'] : 1;
                        $child_component->sale_price = isset($child['salePrice']) ? $child['salePrice'] : null;
                        $child_component->tax = 0;
                        $child_component->discount = 0;
                        $child_component->hourly_rate = isset($child['hourlyRate']) ? $child['hourlyRate'] : null;

                        $child_component->sale_offer_id = $model->id;
                        $child_component->parent_component_id = $component->id;
                        $child_component->save();

                        array_push($component_ids, $child_component->id);
                    }
                }
            };

            $model->components()->whereNotIn('id', $component_ids)->delete();
            $optional_component_ids = [];
            if (isset($request->optionalTypes) && $request->optionalTypes) {
                foreach ($request->optionalTypes as $type) {
                    $component = SaleOfferOptionalComponent::firstOrNew(['id' => isset($type['id']) ? $type['id'] : null]);
                    $component->type = $type['type'];
                    if ($type['type'] === 'service') {

                        if ($request->groupedBy == 'nothing') {
                            $component->product_name = isset($type['name']) ? $type['name'] : null;
                        } else if ($request->groupedBy == 'category') {
                            $productNameArr = [];
                            foreach ($type['productFullDetails'] as $key => $value) {
                                $productNameArr[] = $value['name'];
                            }

                            $component->product_name = isset($productNameArr) ? $productNameArr : null;
                        }
                    } else {
                        $component->product_name = isset($type['productFullDetails']['name']) ? $type['productFullDetails']['name'] : null;
                    }
                    $component->product_id = isset($type['productId']) ? $type['productId'] : null;
                    $component->quantity = $type['quantity'];
                    $component->hourly_rate = isset($type['hourlyRate']) ? $type['hourlyRate'] : 0;
                    $component->price_per_period = isset($type['pricePerPeriod']) ? $type['pricePerPeriod'] : null;
                    $component->payment_period = isset($type['paymentPeriod']['id']) ? $type['paymentPeriod']['id'] : null;
                    $component->tax = isset($type['tax']) ? $type['tax'] : null;
                    $component->duration = isset($type['duration']) ? $type['duration'] : 1;
                    $component->discount = $type['discount'] ?? 0;
                    $component->maintenance_rate = isset($type['maintenanceRate']) ? $type['maintenanceRate'] : null;
                    $component->sale_price = isset($type['salePrice']) ? $type['salePrice'] : null;
                    $component->products = isset($type['products']) ? $type['products'] : null;
                    $component->product_category_id = isset($type['productCategoryId']) ? $type['productCategoryId'] : null;
                    $component->sale_offer_id = $model->id;
                    if ($type['type'] == 'license') {
                        $component->total_netto = $type['productFullDetails']['priceWithoutTax'];
                    } elseif ($type['type'] == 'maintenance') {
                        $component->total_netto = $type['productFullDetails']['maintenancePrice'];
                        $component->total_amount = $type['productFullDetails']['maintenancePrice'] + $type['productFullDetails']['priceWithoutTax'];
                    } elseif ($type['type'] == 'service' && $request->groupedBy == 'nothing') {
                        $totalNetto = ($type['hourlyRate'] * $type['quantity']) - (($type['hourlyRate'] * $type['quantity'] * ($type['discount'] ?? 0)) / 100);
                        $component->total_netto = $totalNetto;
                    } elseif ($type['type'] == 'service' && $request->groupedBy == 'category') {
                        $component->total_netto = $type['nettoTotal'];
                    } else {
                        $component->total_netto = $type['productFullDetails']['nettoTotal'];
                    }

                    // save the SLA values only when the type is 'application'
                    if ($type['type'] == 'application') {
                        $component->sla_infrastructure_id = isset($type['productFullDetails']['slaInfrastructureId']) ? $type['productFullDetails']['slaInfrastructureId'] : null;
                        $component->sla_service_time_id = isset($type['productFullDetails']['slaServiceTimeId']) ? $type['productFullDetails']['slaServiceTimeId'] : null;
                        $component->sla_level_id = isset($type['productFullDetails']['slaLevelId']) ? $type['productFullDetails']['slaLevelId'] : null;
                    }
                    $component->save();
                    array_push($optional_component_ids, $component->id);
                }
            }
            $model->optionalComponents()->whereNotIn('id', $optional_component_ids)->delete();

            $model->netto_total = $model->components()->whereNull('parent_component_id')->sum('total_netto');
            $model->save();

            if ($action == 'updateOrder') {
                $action = 'updateOrderConfirmation';
            } elseif ($action == 'createOrder') {
                $action = 'createOrderConfirmation';
            }
            $content = [
                'moduleAction' => $action,
                'data' => [
                    'offer' => $model->toArray(),
                    'offerProducts' => $model->components?->toArray(),
                    'offerOptionalProducts' => $model->optionalComponents?->toArray(),
                ]
            ];
            GlobalSettingHelper::sendEloAPIRequest($content, $model);
        });
    }

    public static function show($id)
    {
        $model = SaleOffer::findOrFail($id);
        $parent_offer = SaleOffer::find($model->offer_id);
        $data = [
            'id' => $model->id,
            'offerType' => $model->type,
            'offerNumber' => $model->sale_offer_number,
            'coverLetterText' => $model->cover_letter_text,
            'offerDescriptionText' => $model->offer_description_text,
            'company' => $model->company->id,
            'term' => $model->term->id,
            'project' => $model->project?->id,
            'removeFromStatistics' => $model->remove_from_statistics,
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
            'type' => $model->offer_type,
            'receiverType' => $model->receiver_type,
            'groupedBy' => $model->grouped_by,
            'template' => $model->template,
            'createdBy' => $model->created_by,
            'expiryDate' => Carbon::parse($model->expiry_date)->toDateString(),
            'plannedStartDate' => Carbon::parse($model->planned_start_date)->toDateString(),
            'paymentTerms' => $model->payment_terms,
            'createdAt' => Carbon::parse($model->created_at)->toDateString(),
            'updatedAt' => Carbon::parse($model->updated_at)->toDateString(),
            'contactPerson' => $model->contact_person,
            'externalOrderNumber' => $model->external_order_number,
            'identifier' => $model->identifier,
            'isVisibleForCustomer' => (bool)$model->is_visible_for_customer,
            'customer' => [
                'id' => $model->company?->id,
                'companyName' => $model->company?->company_name,
                'vatId' => $model->company?->vat_id,
                'url' => $model->company?->url,
                'type' => $model->company?->type,
                'customerType' => $model->company?->customer_type,
                'companyNumber' => $model->company?->company_number,
                'state' => $model->company?->headQuarters?->state,
                'city' => $model->company?->headQuarters?->city,
                'country' => $model->company?->headQuarters?->relatedCountry?->name,
                'address' => $model->company?->headQuarters?->address_first,
                'code' => $model->company?->headQuarters?->zip,
                'notes' => $model->company?->notes,
                'status' => $model->company?->status,
                'expiryDate' => $model->company?->expiry_dt ? Carbon::parse($model->company?->expiry_dt)->toDateString() : '',
                'termsOfPayment' => $model->company?->terms_of_payment,
                'mergePdfsOnDefault' => $model->company?->merge_pdfs_on_default,
                'externalOrderNumber' => $model->company?->external_order_number
            ],
            'components' => $model->components()->whereNull('parent_component_id')->get()->map(function ($component) use ($model) {
                $productsGroupedByCategory = collect();

                if ($model->grouped_by == 'category' && $component->type == 'service') {
                    // get all products part of this category, even duplicates
                    foreach (($component?->products ?? []) as $product_id) {
                        $product = Product::with('productCategory')->findOrFail($product_id);
                        $productsGroupedByCategory->push($product);
                    }
                }
                return [
                    'id' => $component->id,
                    'type' => $component->type,
                    'isProductNameEdit' => $component->product->is_product_name_edit,
                    'product' => [
                        'id' => $component->product->id,
                        'isProductNameEdit' => $component->product->is_product_name_edit,
                        'articleNumber' => $component->product->article_number,
                        'offerProductName' => $component->product_name ?? '',
                        'name' => $component->product_name ?? $component->product->name,
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
                            'id' => $component->product?->productCategory?->id,
                            'name' => $component->product?->productCategory?->name,
                            'defaultUnit' => $component->product?->productCategory?->default_unit,
                            'isDefaultOnOffers' => $component->product?->productCategory?->is_default_on_offers
                        ]
                    ],
                    'productGroup' => $component?->product?->productGroup?->name,
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
                    'products' => $productsGroupedByCategory->map(function ($product, $index) use ($component) {
                        if (!empty($component->product_name)) {
                            $product_names = $component->product_name[$index];
                        } else {
                            $product_names = $product->name;
                        }
                        return [
                            'id' => $product->id,
                            'articleNumber' => $product->article_number,
                            'isProductNameEdit' => $product->is_product_name_edit,
                            // 'name' =>$component->product_name ?? $product->name,
                            'name' => $product_names,
                            'offerProductName' => $component->product_name ?? '',
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
                                'id' => $product?->productCategory?->id,
                                'name' => $product?->productCategory?->name,
                                'defaultUnit' => $product?->productCategory?->default_unit,
                                'isDefaultOnOffers' => $product?->productCategory?->is_default_on_offers
                            ]
                        ];
                    }),
                    'productCategory' => ProductCategory::where('id', $component->product_category_id)?->first(),
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
                    'children' => $component->children->map(function ($child) use ($model) {
                        $productsGroupedByCategory = collect();
                        if ($model->grouped_by == 'category' && $child->type == 'service') {
                            $productsGroupedByCategory = Product::with('productCategory')->whereIn('id', $child?->products)->get();
                        }
                        return [
                            'id' => $child->id,
                            'type' => $child->type,
                            'isProductNameEdit' => $child->product->is_product_name_edit,
                            'product' => [
                                'id' => $child->product->id,
                                'articleNumber' => $child->product->article_number,
                                'isProductNameEdit' => $child->product->is_product_name_edit,
                                'name' => $child->product_name ??  $child->product->name,
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
                                    'id' => $child->product?->productCategory?->id,
                                    'name' => $child->product?->productCategory?->name,
                                    'defaultUnit' => $child->product?->productCategory?->default_unit,
                                    'isDefaultOnOffers' => $child->product?->productCategory?->is_default_on_offers
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
                                    'isProductNameEdit' => $product->is_product_name_edit,
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
                                        'id' => $product?->productCategory?->id,
                                        'name' => $product?->productCategory?->name,
                                        'defaultUnit' => $product?->productCategory?->default_unit,
                                        'isDefaultOnOffers' => $product?->productCategory?->is_default_on_offers
                                    ]
                                ];
                            }),
                            'productCategory' => ProductCategory::where('id', $child->product_category_id)?->first(),
                            'offerId' => $child->sale_offer_id,
                        ];
                    })
                ];
            }),
            'optionalComponents' => $model->optionalComponents->map(function ($component) use ($model) {
                $productsGroupedByCategory = collect();
                if ($model->grouped_by == 'category' && $component->type == 'service') {
                    $productsGroupedByCategory = Product::with('productCategory')->whereIn('id', $component?->products)->get();
                }
                return [
                    'id' => $component->id,
                    'type' => $component->type,
                    'isProductNameEdit' => $component->product->is_product_name_edit,
                    'product' => [
                        'id' => $component->product->id,
                        'articleNumber' => $component->product->article_number,
                        'name' => $component->product_name ?? $component->product->name,
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
                            'id' => $component->product?->productCategory?->id,
                            'name' => $component->product?->productCategory?->name,
                            'defaultUnit' => $component->product?->productCategory?->default_unit,
                            'isDefaultOnOffers' => $component->product?->productCategory?->is_default_on_offers
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
                    'products' => $productsGroupedByCategory->map(function ($product, $index)  use ($component) {

                        if (!empty($component->product_name)) {
                            $product_names = $component->product_name[$index];
                        } else {
                            $product_names = $product->name;
                        }

                        return [
                            'id' => $product->id,
                            'articleNumber' => $product->article_number,
                            'name' =>   $product_names,
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
                                'id' => $product?->productCategory?->id,
                                'name' => $product?->productCategory?->name,
                                'defaultUnit' => $product?->productCategory?->default_unit,
                                'isDefaultOnOffers' => $product?->productCategory?->is_default_on_offers
                            ]
                        ];
                    }),
                    'productCategory' => ProductCategory::where('id', $component->product_category_id)?->first(),
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
        ];
        if ($model->type == 'order') {
            $data['orderStatus'] = $model->order_status;
        } else {
            $data['offerStatus'] = $model->offer_status;
        }
        return response()->json([
            'data' => $data
        ]);
    }

    public static function update($request, $id)
    {
        //Update Sale Offer
        $model = SaleOffer::where('id', $id)->first();
        try {
            self::saveData($model, $request, 'update' . ucfirst($request->offerType ?? 'offer'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
        return response()->json(['success' => true, 'message' => 'Record updated successfully.'], 200);
    }

    public static function destroy($id)
    {
        $model = SaleOffer::findOrFail($id);
        $model->components->each->delete();
        $model->optionalComponents->each->delete();
        $model->delete();
        $model->deleted_at = Carbon::now()->toIso8601ZuluString();
        $content = [
            'moduleAction' => 'delete' . ucfirst($model->offer_type),
            'data' => [
                'offer' => $model->toArray(),
                'offerProducts' => $model->components?->toArray(),
            ]
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    /**
     * gets the elo request data for offer and offer confirmation
     * @param int $id
     * @return JSONResponse
     */
    public static function getELORequestData($id)
    {
        $model = SaleOffer::findOrFail($id);
        return response()->json([
            'data' => [
                'offer' => $model->toArray(),
                'offerProducts' => $model->components?->toArray(),
                'offerOptionalProducts' => $model->optionalComponents?->toArray(),
            ]
        ]);
    }

    private static function applySorting($collection, $sortBy, $sortOrder)
    {
        return $collection->sortBy($sortBy, SORT_REGULAR, $sortOrder == 'asc' ? false : true)->values();
    }

    public static function createInvoices($id)
    {
        $offer_confirmation = SaleOffer::findOrFail($id);
        $groups = $offer_confirmation->components()->whereNull('parent_component_id')->get()->groupBy('type');
        foreach ($groups as $key => $group) {
            $invoice_id = null;
            $invoice = new Invoice();
            try {
                DB::transaction(function () use ($group, $offer_confirmation, $invoice, $key) {
                    $invoice->invoice_type = 'invoice';
                    $invoice->due_date = Carbon::parse($offer_confirmation->expiry_date);
                    $invoice->start_date = $offer_confirmation->planned_start_date ? Carbon::parse($offer_confirmation->planned_start_date) : null;
                    $invoice->end_date = $offer_confirmation->expiry_date ? Carbon::parse($offer_confirmation->expiry_date) : null;
                    if (!$offer_confirmation?->company?->default_payment_period) {
                        throw new Exception('Kindly assign a default payment period to the respective customer to continue');
                    }
                    $invoice->invoice_period = $offer_confirmation?->company->default_payment_period;
                    $invoice->terms_of_payment = $offer_confirmation->term_id;
                    $invoice->custom_notes_field = null;
                    $invoice->total_amount = $group->sum(function ($item) {
                        $sum = 0;
                        if ($item->type == 'license') {
                            $sum = ($item->quantity * $item->sale_price) - (($item->quantity * $item->sale_price) * $item->discount) / 100;
                        } elseif ($item->type == 'maintenance') {
                            $sum = $item->total_amount;
                        } elseif ($item->type == 'service') {
                            $sum = ($item->quantity * $item->hourly_rate) - (($item->quantity * $item->hourly_rate) * $item->discount) / 100;
                        } elseif ($item->type == 'application') {
                            $sum = ($item->quantity * $item->hourly_rate) - (($item->quantity * $item->hourly_rate) * $item->discount) / 100;
                        } elseif ($item->type == 'hosting') {
                            $sum = ($item->quantity * $item->price_per_period) - (($item->quantity * $item->price_per_period) * $item->discount) / 100;
                        } elseif ($item->type == 'cloud') {
                            $sum = ($item->quantity * $item->duration * $item->sale_price) - (($item->quantity * $item->duration * $item->sale_price) * $item->discount) / 100;
                        }
                        return $sum;
                    });
                    $invoice->total_amount_without_tax = $group->sum('total_netto');
                    $invoice->total_tax_amount = $group->sum(function ($item) {
                        $sum = 0;
                        if ($item->type == 'license') {
                            $sum = (($item->quantity * $item->sale_price) * $item->tax) / 100;
                        } elseif ($item->type == 'maintenance') {
                            $sum = ($item->total_amount * $item->tax) / 100;
                        } elseif ($item->type == 'service') {
                            $sum = (($item->quantity * $item->hourly_rate) * $item->tax) / 100;
                        } elseif ($item->type == 'application') {
                            $sum = (($item->quantity * $item->hourly_rate) * $item->tax) / 100;
                        } elseif ($item->type == 'hosting') {
                            $sum = (($item->quantity * $item->price_per_period) * $item->tax) / 100;
                        } elseif ($item->type == 'cloud') {
                            $sum = (($item->quantity * $item->duration * $item->sale_price) * $item->tax) / 100;
                        }
                        return $sum;
                    });
                    $invoice->invoice_category = $key == 'application' ? 'ams' : ($key == 'cloud' ? 'cloud-software' : $key);
                    $invoice->user_id = $offer_confirmation->created_by;
                    $invoice->company_id = $offer_confirmation->company_id;
                    $invoice->system_id = null;
                    $invoice->project_id = $offer_confirmation->project_id;
                    $invoice->grouped_by = $offer_confirmation->grouped_by;
                    $invoice->payment_terms = $offer_confirmation->payment_terms;
                    $invoice->contact_person = $offer_confirmation->contact_person;
                    $invoice->external_order_number = $offer_confirmation->external_order_number ?? null;
                    $invoice->draft_status_change_date = Carbon::now();

                    $invoice->save();
                });
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            $invoice_id = $invoice->id;
            if ($key == 'service' && $offer_confirmation->grouped_by == 'category') {
                foreach ($group as $component) {
                    $tax_rate = (($component->quantity * $component->hourly_rate) * $component->tax) / 100;
                    $data = [
                        'invoice_id' => $invoice_id,
                        'product_ids' => $component->products,
                        'category_id' => $component->product_category_id,
                        'quantity' => $component->quantity,
                        'hourly_rate' => $component->hourly_rate,
                        'discount' => $component->discount,
                        'tax' => $component->tax,
                        'tax_rate' => $tax_rate,
                        'netto_total' => $component->total_netto,
                        'additional_description' => $component->additional_description,
                    ];
                    ProductInvoiceCategory::create($data);
                }
            } else {
                foreach ($group as $component) {
                    $total_price = 0;
                    if ($component->type == 'license') {
                        $price = ($component->quantity * $component->sale_price) - (($component->quantity * $component->sale_price) * $component->discount) / 100;
                        $tax_rate = ($price * $component->tax) / 100;
                        $total_price = $price + $tax_rate;
                    } elseif ($component->type == 'maintenance') {
                        $total_price = $component->total_amount;
                    } elseif ($component->type == 'service') {
                        $price = ($component->quantity * $component->hourly_rate) - (($component->quantity * $component->hourly_rate) * $component->discount) / 100;
                        $tax_rate = ($price * $component->tax) / 100;
                        $total_price = $price + $tax_rate;
                    } elseif ($component->type == 'application') {
                        $price = ($component->quantity * $component->hourly_rate) - (($component->quantity * $component->hourly_rate) * $component->discount) / 100;
                        $tax_rate = ($price * $component->tax) / 100;
                        $total_price = $price + $tax_rate;
                    } elseif ($component->type == 'hosting') {
                        $price = ($component->quantity * $component->price_per_period) - (($component->quantity * $component->price_per_period) * $component->discount) / 100;
                        $tax_rate = ($price * $component->tax) / 100;
                        $total_price = $price + $tax_rate;
                    } elseif ($component->type == 'cloud') {
                        $price = ($component->quantity * $component->duration * $component->sale_price) - (($component->quantity * $component->duration * $component->sale_price) * $component->discount) / 100;
                        $tax_rate = ($price * $component->tax) / 100;
                        $total_price = $price + $tax_rate;
                    }
                    // attach the products to the invoice
                    $invoice->products()->attach($component->product_id, [
                        'sale_price' => $component->sale_price ?? 0, 'discount' => $component->discount,
                        'quantity' => $component->quantity, 'total_price' => $total_price,
                        'price_without_tax' => $component->total_netto,
                        'tax' => $component->tax,
                        'hourly_rate' => $component->hourly_rate,
                        'price_per_period' => $component->price_per_period,
                        'service_hours' => $component->service_hours,
                        'maintenance_rate' => $component->maintenance_rate,
                        'payment_period' => $component->payment_period,
                        'duration' => $component->duration,
                        'additional_description' => $component->additional_description,
                    ]);
                    // fetch the created product_invoice
                    $product_invoice = $invoice->products()->latest()->first();
                    if ($component->children) {
                        // attach the children
                        foreach ($component->children as $child) {
                            $total_price = 0;
                            if ($child->type == 'license') {
                                $price = ($child->quantity * $child->sale_price) - (($child->quantity * $child->sale_price) * $child->discount) / 100;
                                $tax_rate = ($price * $child->tax) / 100;
                                $total_price = $price + $tax_rate;
                            } elseif ($child->type == 'maintenance') {
                                $total_price = $child->total_amount;
                            } elseif ($child->type == 'service') {
                                $price = ($child->quantity * $child->hourly_rate) - (($child->quantity * $child->hourly_rate) * $child->discount) / 100;
                                $tax_rate = ($price * $child->tax) / 100;
                                $total_price = $price + $tax_rate;
                            } elseif ($child->type == 'application') {
                                $price = ($child->quantity * $child->hourly_rate) - (($child->quantity * $child->hourly_rate) * $child->discount) / 100;
                                $tax_rate = ($price * $child->tax) / 100;
                                $total_price = $price + $tax_rate;
                            } elseif ($child->type == 'hosting') {
                                $price = ($child->quantity * $child->price_per_period) - (($child->quantity * $child->price_per_period) * $child->discount) / 100;
                                $tax_rate = ($price * $child->tax) / 100;
                                $total_price = $price + $tax_rate;
                            } elseif ($child->type == 'cloud') {
                                $price = ($child->quantity * $child->duration * $child->sale_price) - (($child->quantity * $child->duration * $child->sale_price) * $child->discount) / 100;
                                $tax_rate = ($price * $child->tax) / 100;
                                $total_price = $price + $tax_rate;
                            }
                            $invoice->products()->attach($child->product_id, [
                                'sale_price' => $child->sale_price ?? 0, 'discount' => $child->discount,
                                'quantity' => $child->quantity, 'total_price' => $total_price,
                                'price_without_tax' => $child->total_netto ?? 0,
                                'tax' => $child->tax,
                                'hourly_rate' => $child->hourly_rate,
                                'price_per_period' => $child->price_per_period,
                                'service_hours' => $child->service_hours,
                                'maintenance_rate' => $child->maintenance_rate,
                                'payment_period' => $child->payment_period,
                                'duration' => $child->duration,
                                'additional_description' => $child->additional_description,
                                'parent_product_invoice_id' => $product_invoice->pivot->id
                            ]);
                        }
                    }
                }
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Invoices created successfully'
        ], 200);
    }

    public static function offerForDocumentGeneration($id)
    {
        $offer = SaleOffer::findOrFail($id);
        $parent_offer = SaleOffer::find($offer->offer_id);
        $user_profile = UserProfileData::where('user_id', $offer->created_by)->first();
        $offer_data = [
            'id' => $offer->id,
            'type' => $offer->offer_type,
            'offerType' => $offer->type,
            'orderStatus' => $offer->order_status,
            'offerStatus' => $offer->offer_status,
            'companyId' => $offer->company_id,
            'termId' => $offer->term_id,
            'receiverType' => $offer->receiver_type,
            'template' => $offer->template,
            'groupedBy' => $offer->grouped_by,
            'expiryDate' => Carbon::parse($offer->expiry_date)->toDateString(),
            'paymentTerms' => $offer->payment_terms,
            'createdBy' => $offer->created_by,
            'coverLetterText' => $offer->cover_letter_text,
            'offerDescriptionText' => $offer->offer_description_text,
            'contactPerson' => $offer->contact_person,
            'removeFromStatistics' => $offer->remove_from_statistics,
            'plannedStartDate' => Carbon::parse($offer->planned_start_date)->toDateString(),
            'externalOrderNumber' => $offer->external_order_number,
            'offerNumber' => $offer->sale_offer_number,
            'output' => 'pdf',
            'userFirstName' => $user_profile?->first_name,
            'userLastName' => $user_profile?->last_name,
            'userEmail' => $user_profile?->email,
            'userPhone' => $user_profile?->phone ?? $user_profile?->mobile,
            'updatedTime' => Carbon::now()->timestamp,
            'offer' => $parent_offer ? [
                'id' => $parent_offer->id,
                'offerNumber' => $parent_offer->sale_offer_number,
                'company' => $parent_offer->company->company_name,
                'terms' => $parent_offer->term->name,
                'receiverType' => $parent_offer->receiver_type,
                'project' => $parent_offer->project?->name,
                'type' => $parent_offer->offer_type,
                'totalNetto' => $parent_offer->components->sum('total_netto')
            ] : null,
            'company' => $offer->company ? [
                'id' => $offer->company?->id,
                'companyName' => $offer->company?->company_name,
                'vatId' => $offer->company?->vat_id,
                'url' => $offer->company?->url,
                'type' => $offer->company?->type,
                'customerType' => $offer->company?->customer_type,
                'companyNumber' => $offer->company?->company_number,
                'state' => $offer->company?->headQuarters?->state,
                'city' => $offer->company?->headQuarters?->city,
                'country' => $offer->company?->headQuarters?->country,
                'address' => $offer->company?->headQuarters?->address_first,
                'code' => $offer->company?->headQuarters?->zip,
                'notes' => $offer->company?->notes,
                'status' => $offer->company?->status,
                'expiryDate' => $offer->company?->expiry_dt ? Carbon::parse($offer->company?->expiry_dt)->toDateString() : '',
                'termsOfPayment' => $offer->company?->terms_of_payment,
                'mergePdfsOnDefault' => $offer->company?->merge_pdfs_on_default ?? 0
            ] : null,
            'types' => $offer->components()->whereNull('parent_component_id')->get()->map(function ($component) use ($offer) {
                $productsGroupedByCategory = collect();
                if ($offer->grouped_by == 'category' && $component->type == 'service') {
                    // get all products part of this category, even duplicates
                    foreach (($component?->products ?? []) as $product_id) {
                        $product = Product::with('productCategory')->findOrFail($product_id);
                        $productsGroupedByCategory->push($product);
                    }
                }
                return [
                    ...self::getTypeDetails($component, $productsGroupedByCategory, $offer),
                    'productFullDetails' => self::getTypeDetails($component, $productsGroupedByCategory, $offer)
                ];
            }),
            'optionalTypes' => $offer->optionalComponents->map(function ($component) use ($offer) {
                $productsGroupedByCategory = collect();
                if ($offer->grouped_by == 'category' && $component->type == 'service') {
                    // get all products part of this category, even duplicates
                    foreach (($component?->products ?? []) as $product_id) {
                        $product = Product::with('productCategory')->findOrFail($product_id);
                        $productsGroupedByCategory->push($product);
                    }
                }
                return [
                    ...self::getTypeDetails($component, $productsGroupedByCategory, $offer),
                    'productFullDetails' => self::getTypeDetails($component, $productsGroupedByCategory, $offer)
                ];
            })
        ];

        return $offer_data;
    }

    private static function getTypeDetails($component, $productsGroupedByCategory, $offer)
    {
        $payment_period = PaymentPeriod::find($component->payment_period);
        return [
            'type' => $component->type,
            'componentId' => $component->id,
            'productId' => $component->product->id,
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
                    'id' => $component->product?->productCategory?->id,
                    'name' => $component->product?->productCategory?->name,
                    'defaultUnit' => $component->product?->productCategory?->default_unit,
                    'isDefaultOnOffers' => $component->product?->productCategory?->is_default_on_offers
                ]
            ],
            'quantity' => $component->quantity,
            'duration' => $component->duration,
            'hourlyRate' => $component->hourly_rate,
            'pricePerPeriod' => $component->price_per_period,
            'salePrice' => $component->sale_price,
            'maintenanceRate' => $component->maintenance_rate,
            'tax' => $component->tax,
            'paymentPeriod' => $payment_period ? [
                'id' => $payment_period->id,
                'name' => $payment_period->name,
                'billingCycle' => $payment_period->billing_cycle,
                'createdAt' => Carbon::parse($payment_period->created_at)->format('d/m/y')
            ] : null,
            'discount' => $component->discount,
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
                        'id' => $product?->productCategory?->id,
                        'name' => $product?->productCategory?->name,
                        'defaultUnit' => $product?->productCategory?->default_unit,
                        'isDefaultOnOffers' => $product?->productCategory?->is_default_on_offers
                    ]
                ];
            }),
            'offerId' => $component->sale_offer_id,
            'productCategory' => ProductCategory::where('id', $component->product_category_id)?->first(),
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
            'children' => collect($component?->children ?? [])->map(function ($child) use ($offer) {
                $productsGroupedByCategory = collect();
                if ($offer->grouped_by == 'category' && $child->type == 'service') {
                    $productsGroupedByCategory = Product::with('productCategory')->whereIn('id', $child?->products)->get();
                }
                $payment_period = PaymentPeriod::find($child->payment_period);
                return [
                    'id' => $child->id,
                    'type' => $child->type,
                    'productId' => $child->product->id,
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
                            'id' => $child->product?->productCategory?->id,
                            'name' => $child->product?->productCategory?->name,
                            'defaultUnit' => $child->product?->productCategory?->default_unit,
                            'isDefaultOnOffers' => $child->product?->productCategory?->is_default_on_offers
                        ]
                    ],
                    'quantity' => $child->quantity,
                    'duration' => $child->duration,
                    'hourlyRate' => $child->hourly_rate,
                    'pricePerPeriod' => $child->price_per_period,
                    'salePrice' => $child->sale_price,
                    'maintenanceRate' => $child->maintenance_rate,
                    'tax' => $child->tax,
                    'paymentPeriod' => $payment_period ? [
                        'id' => $payment_period->id,
                        'name' => $payment_period->name,
                        'billingCycle' => $payment_period->billing_cycle,
                        'createdAt' => Carbon::parse($payment_period->created_at)->format('d/m/y')
                    ] : null,
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
                                'id' => $product?->productCategory?->id,
                                'name' => $product?->productCategory?->name,
                                'defaultUnit' => $product?->productCategory?->default_unit,
                                'isDefaultOnOffers' => $product?->productCategory?->is_default_on_offers
                            ]
                        ];
                    }),
                    'productCategory' => ProductCategory::where('id', $child->product_category_id)?->first(),
                    'offerId' => $child->sale_offer_id,
                    'articleNumber' => $child->product->article_number,
                    'name' => $child->product->article_number,
                    'nettoTotal' => $child->total_netto,
                    'taxRate' => $child->tax,
                ];
            }),
            'articleNumber' => $component->product->article_number,
            'name' => $component->product->article_number,
            'nettoTotal' => $component->total_netto,
            'taxRate' => $component->tax,
        ];
    }
}