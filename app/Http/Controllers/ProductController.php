<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EloVersion;
use App\Models\GlobalSetting;
use App\Models\PartnerManagementDiscount;
use App\Models\PriceList;
use App\Models\Product;
use App\Models\ProductRule;
use App\Models\ProductChildren;
use App\Models\ProductDependency;
use App\Models\ProductGroup;
use App\Models\ProductVersion;
use App\Traits\NestedArrayTraits;
use App\Traits\CustomHelper;
use Facades\App\Services\ProductService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request as staticRequest;


class ProductController extends Controller
{

    use NestedArrayTraits, CustomHelper;


    /**
     *Runs when instance is initiated
     */
    public function __construct()
    {
        $this->middleware('check.permission:product.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:product.create', ['only' => ['store']]);
        $this->middleware('check.permission:product.edit', ['only' => ['update']]);
        $this->middleware('check.permission:product.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $per_page = $request->perPage ?? 25;
        $model = new Product;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }

        //from partner portal
        if ($request->partnerId) {
            if ($request->priceListType == 'docshero_partner_price_list') {
                $priceListIds = PriceList::where('type', $request->priceListType)->pluck('id')->toArray();
                $model = $model->whereIn('product_price_id', $priceListIds);
            } elseif ($request->priceListType == 'partner_price_list') {
                $priceListIds = PriceList::where('type', $request->priceListType)
                    ->where('partner_id', $request->partnerId)->pluck('id')->toArray();
                $model = $model->whereIn('product_price_id', $priceListIds);
            } else {
                $priceListIds = PriceList::where(function ($query) use ($request) {
                    $query->where('partner_id', $request->partnerId)->where('type', 'partner_price_list');
                })->orWhere('type', 'docshero_partner_price_list')->pluck('id')->toArray();
                $model = $model->whereIn('product_price_id', $priceListIds);
            }
        }
        $partnerDiscount = GlobalSetting::where('key', 'partnerDiscountRate')->first();
        $products = $model
            ->filter(staticRequest::only('search', 'status', 'productCategoryId', 'productSoftwareId', 'type', 'productPriceId'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($product) => [
                'id' => $product->id,
                'articleNumber' => $product->article_number,
                'manufacturerNumber' => $product->manufacturer_article_number,
                'name' => $product->name,
                'productCategory' => $product->productCategory?->name,
                'type' => $product->payment_details_type,
                'status' => $product->status ? 'active' : 'deactive',
                'salePrice' => $product->sale_price,
                'pricePerPeriod' => $product->price_per_period,
                'profit' => $product->profit,
                'productSoftware' => $product->productSoftware?->name,
                'productGroup' => $product->productGroup?->name,
                'dailyRate/fixPrice' => $product->payment_details_type == 'ams' ? $product->ams_type == 'contingent' ? $product->daily_rate  : $product->fixed_price : 0
            ]);
        return response()->json(['products' => $products, 'partnerDiscount' => @$partnerDiscount->value ?? 0], 200);
    }

    /**
     * Display a listing of products on base of software payment details type.
     *
     * @return \Illuminate\Http\Response
     */
    public function productsWithQuantity(Request $request)
    {
        $products = new Product();
        $partnerDiscount = null;
        $per_page = $request->perPage ?? 10;
        // if type is an array then products of multiple type is being requested
        if (is_array($request->type)) {
            $products = $products->whereIn("payment_details_type", $request->type);
        } else if ($request->type) {
            $products = $products->where("payment_details_type", in_array($request->type, ['software', 'service', 'pauschal', 'ams', 'cloud-software', 'hosting']) ? $request->type : "software");
        }

        //Filter the products by given product category id.
        if ($request->productCategoryId) {
            $products = $products->where('product_category_id', $request->productCategoryId);
        }

        // if companyId is in the request, filter the products by the default price list of the company
        if ($request->companyId) {
            $company_price_lists = Company::findOrFail($request->companyId)->priceLists()->pluck('price_list_id');
            $products = $products->whereIn("product_price_id", $company_price_lists);
        } // filter the products by price list id
        else if ($request->productPriceId) {
            $products = $products->where("product_price_id", $request->productPriceId);
        } // filter by partner_price_list
        else if ($request->partnerId) {
            $priceListIds = PriceList::where('partner_id', $request->partnerId)
                ->where('type', 'partner_price_list')->pluck('id')->toArray();
            $products = $products->whereIn('product_price_id', $priceListIds);
        } else if ($request->docsheroPriceList) {
            $priceListIds = PriceList::where('type', 'docshero_partner_price_list')->pluck('id')->toArray();
            $products = $products->whereIn('product_price_id', $priceListIds);
            $globalPrice = GlobalSetting::where("key", "LIKE", "partnerDiscountRate")->first();
            $partnerDiscount = $globalPrice->value ?? 0.00;
        } else if($request->forPartnerPortal){
            if ($request->priceListType == 'partner') {
                $priceListIds = PriceList::where('type', 'docshero_partner_price_list')
                    ->where(function ($query) use ($request){
                      $query->where('partner_id', $request->tenantId);
                      $query->orWhereNull('partner_id');
                    })->pluck('id')->toArray();
                $products = $products->whereIn('product_price_id', $priceListIds);
                $globalPrice = GlobalSetting::where("key", "LIKE", "partnerDiscountRate")->first();
                $partnerDiscount = $globalPrice->value ?? 0.00;
            } else if($request->priceListType == 'own') {
                $priceListIds = PriceList::where('partner_id', $request->tenantId)
                    ->where('type', 'partner_price_list')->pluck('id')->toArray();
                $products = $products->whereIn('product_price_id', $priceListIds);
            }
        }
        // by default filter the products who have a default price list set
        else {
            $products = $products->whereHas('productPrice', function ($query) {
                $query->where('is_default', 1)->where('status', 'active');
            });
        }

        $products = $products->orderBy('article_number')
            ->filter(staticRequest::only('search', 'status', 'productGroupId', 'productSoftwareId'))
            ->paginate($per_page)
            ->through(function ($product) use ($partnerDiscount) {
                $data = [
                    'id' => $product->id,
                    'articleNumber' => $product->article_number,
                    'manufacturerNumber' => $product->manufacturer_article_number,
                    'isProductNameEdit' => $product->is_product_name_edit,
                    'name' => $product->name,
                    'listingPrice' => $product->listing_price,
                    'status' => $product->status ? 'active' : 'deactive',
                    'salePrice' => $product->sale_price,
                    'profit' => $product->profit,
                    'discount' => $partnerDiscount != null ? $partnerDiscount : $product->discount,
                    'quantity' => $product->payment_details_type == 'pauschal' ? $product->quantity : 1,
                    'tax' => $product->tax,
                    'maintenancePrice' => $product->maintanence_price,
                    'maintenanceRate' => $product->maintenance_rate,
                    'productGroup' => $product->productGroup?->name,
                    'category' => [
                        "id" => $product?->productCategory?->id,
                        "name" => $product?->productCategory?->name,
                        "defaultUnit" => $product?->productCategory?->default_unit,
                        "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers,
                        "serviceContingent" => $product?->productCategory?->service_contingent,
                        'productType' => $product?->productCategory?->product_type,
                    ],
                    //Additional info
                    'description' => $product->description,
                    'unit' => $product->productUnit ? [
                        'id' => $product->productUnit->id,
                        'name' => $product->productUnit->name,
                        'shortName' => $product->productUnit->short_name,
                    ] : null,
                    'type' => $product->payment_details_type,
                    'paymentPeriod' => $product->paymentPeriod ? [
                        'id' => $product->paymentPeriod?->id,
                        'name' => $product->paymentPeriod?->name,
                        'billingCycle' => $product->paymentPeriod?->billing_cycle,
                        'createdAt' => Carbon::parse($product->paymentPeriod?->created_at)->format('d/m/y'),
                    ] : null,
                    'productSoftware' => $product->productSoftware,
                    "rules" => $product->rules->map(function ($item) {
                        return [
                            'id' => $item->rule_id
                        ];
                    }),
                ];
                if ($product->payment_details_type == 'service') {
                    $data['hourlyRate'] = $product->hourly_rate;
                    $data['dailyRate'] = $product->daily_rate;
                } else if ($product->payment_details_type == 'ams') {
                    $data['hourlyRate'] = $product->hourly_rate;
                    $data['dailyRate'] = $product->daily_rate;
                    $data['serviceHours'] = $product->service_hours;
                    $data['serviceDays'] = $product->service_days;
                    $data['fixedPrice'] = $product->fixed_price ?? 0;
                } else if ($product->payment_details_type == 'hosting') {
                    $data['pricePerPeriod'] = $product->price_per_period;
                }
                return $data;
            });
        return response()->json([
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $get_listing = ProductService::getNestedLayoutForProductChildrens();
        return response()->json([
            'groups' => ProductGroup::orderBy('name')
                ->get()
                ->map(fn ($group) => [
                    'id' => $group->id,
                    'name' => $group->name,
                ]),
            'versions' => EloVersion::orderBy('name')
                ->get()
                ->map(fn ($version) => [
                    'id' => $version->id,
                    'name' => $version->name,
                ]),
            'products' => ProductVersion::orderBy('version')
                ->get()
                ->map(fn ($item) => [
                    'version_id' => $item->id,
                    $item->version => [
                        'product_id' => $item->product->id,
                        'product_name' => $item->product->name,
                    ],
                ]),
            'dependencies' => $get_listing['childProducts'],
            'servicesChildrens' => $get_listing['servicesChildrens'],
        ], 200);
    }

    /**
     * Display a listing of the versions with nested products.
     *
     * @return [versions] user object
     */
    public function showDependencies()
    {
        $product_versions = ProductVersion::get();
        $model_collect = $product_versions->map(function ($item) {
            return [
                'version_id' => $item->id,
                'product_id' => $item->product->id,
                'version' => $item->version,
                'product_name' => $item->product->name,
            ];
        });
        return response()->json([
            'products' => $model_collect
        ], 200);
    }

    /**
     * Create data of product
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "status" => "required|boolean",
            "tax" => "required_unless:paymentDetailsType,=,traveling",
            "paymentDetailsType" => "required|in:software,service,pauschal,ams,cloud-software,hosting,traveling",
          //  "unit" => "required_unless:paymentDetailsType,=,hosting,traveling",
            "productSoftware" => "required_unless:paymentDetailsType,=,hosting,traveling",
            "productPrice" => "required_unless:paymentDetailsType,=,hosting",
            "productGroup" => "required_unless:paymentDetailsType,=,cloud-software,traveling",
        ]);

        if ($request->paymentDetailsType == "software" || $request->paymentDetailsType == "cloud-software") {
            $request->validate([
                "manufacturerNumber" => "required|string",
                "executionNumber" => "required|string",
                "recurringPayment" => "required|boolean",
                "paymentPeriod" => "required",
                "listingPrice" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
                "manufacturerDiscount" => "required",
                "profit" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
                "versions" => "required",
                "eloVersion" => "required_if:paymentDetailsType,=,software",
                "salePrice" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
            ]);
            if ($request->paymentDetailsType == "software") {
                $request->validate([
                    "maintanencePrice" => "required|string",
                    "maintanenceRate" => "required|string",
                ]);
            }
        } else if ($request->paymentDetailsType == "hosting") {
            $request->validate([
                "productCategoryId" => "required",
                "paymentPeriod" => "required",
            ]);
        } else if ($request->paymentDetailsType == "service") {
            $request->validate([
                "hourlyRate" => "required",
                "dailyRate" => "required",
                "quantity" => "required",
                "productCategoryId" => "required",
                "salePrice" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
            ]);
        } else if ($request->paymentDetailsType == "pauschal") {
            $request->validate([
                "quantity" => "required",
                "productCategoryId" => "required",
                "salePrice" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
            ]);
        } else if ($request->paymentDetailsType == "ams") {
            $request->validate([
                "amsType" => "required|in:contingent,fix_price",
                "hourlyRate" => "required_if:amsType,==,contingent",
                "dailyRate" => "required_if:amsType,==,contingent",
                "paymentPeriod" => "required_if:amsType,==,contingent",
                //   "serviceHours" => "required",
                //  "serviceDays" => "required",
                "productCategoryId" => "required",
                //  "salePrice" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
                "fixedPrice" => "required|regex:/^-?\d+(\.\d{1,2})?$/"
            ]);
        } else if ($request->paymentDetailsType == "traveling") {
            $request->validate([
                "salePrice" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
            ]);
        }


        /**calling the product service create to store the products */
        $data = ProductService::create($request->all());
        if (!empty($request['rules'])) {
            $rulesIds = collect($request['rules'])->pluck('id')->toArray();

            foreach ($rulesIds as $id) {
                $productRule = new ProductRule();
                $productRule->rule_id = $id;
                $productRule->product_id = $data['id'] ?? null;
                $productRule->save();
            }
        }
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Product']), 'data' => $data["versions"], 'id' => $data["id"]], 200);
    }

    /**
     * Upload file for product service
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function uploadFiles(Request $request)
    {
        return ProductService::uploadFiles($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $product_versions = $product->versions;
        $groups = ProductGroup::get();
        $is_software = $product->payment_details_type == 'software' || $product->payment_details_type == 'cloud-software' || $product->payment_details_type == 'hosting';
        $versions = [];
        if ($is_software) {
            $versions = $product_versions->map(function ($product_version) use ($groups) {
                $parent_array = [];
                foreach ($groups as $group) {
                    $products = $group->products;
                    foreach ($products as $product) {
                        $parent_array[$group->name][$product->name][$product->id] = [];
                        $versions = $product->versions->pluck('version', 'id');
                        $new_key = [];
                        foreach ($versions as $key => $version) {
                            $exploded = explode(".", $version);
                            $concat_version_parts = "";
                            $get_all_parents = "";
                            $version_part_count = count($exploded);
                            for ($i = 0; $i < $version_part_count; $i++) {
                                $versionPart = $exploded[$i];

                                $currParent = $concat_version_parts . "X" . str_repeat(".X", $version_part_count - $i - 1);
                                $concat_version_parts .= $versionPart;

                                $concat_version_parts .= ".";
                                $get_all_parents .= $currParent;
                                $get_all_parents .= "|";
                            }
                            $get_all_parents = substr($get_all_parents, 0, -1);
                            $new_key = NestedArrayTraits::set($new_key, $get_all_parents, [
                                'value' => substr($concat_version_parts, 0, -1),
                                'productId' => $product->id,
                                'versionId' => $key,
                                'selectedDependent' => ProductDependency::where('product_version_id', $product_version->id)
                                    ->where('dependent_version_id', $key)->count() < 1 ? false : true,
                                'selectedChildren' => ProductChildren::where('product_version_id', $product_version->id)
                                    ->where('child_version_id', $key)->count() < 1 ? false : true,
                                'id' => $product->article_number,
                            ]);
                            $parent_array[$group->name][$product->name][$product->id] = $new_key;
                        }
                    }
                }
                return [
                    "id" => $product_version->id,
                    "name" => $product_version->version,
                    "dependentAndChildProducts" => $parent_array,
                    'productFiles' => $product_version->files->map(fn ($file) => [
                        'id' => $file->id,
                        'storage_name' => $file->storage_name,
                        'name' => $file->viewable_name,
                        'size' => $file->storage_size ?? null,
                    ]),
                    'parameters' => $product_version->productParameters->map(fn ($params) => [
                        'id' => $params->id,
                        'type' => $params->type,
                        'key' => $params->key,
                        'value' => $params->value,
                        'file' => $params->file_name,
                    ]),
                    'installations' => $product_version->productInstallations->map(fn ($installation) => [
                        'id' => $installation->id,
                        'subject' => $installation->subject,
                        'time' => $installation->time_needed ?? '',
                        'detail' => $installation->details ?? '',
                    ]),
                    'configurations' => $product_version->productConfigurations->map(fn ($configuration) => [
                        'id' => $configuration->id,
                        'subject' => $configuration->subject,
                        'time' => $configuration->time_needed ?? '',
                        'detail' => $configuration->details ?? '',
                    ]),
                    'serviceProductsChildrens' => ProductService::getNestedLayoutForProductVersionChildrens($product_version->id) ?? [],
                    'windowsPremiseRoutine' => $product_version->routine?->premise_windows_install_routine,
                    'windowsPublicRoutine' => $product_version->routine?->public_windows_install_routine,
                    'windowsPrivateRoutine' => $product_version->routine?->private_windows_install_routine,
                    'macPremiseRoutine' => $product_version->routine?->premise_mac_install_routine,
                    'macPublicRoutine' => $product_version->routine?->public_mac_install_routine,
                    'macPrivateRoutine' => $product_version->routine?->private_mac_install_routine,
                    'linuxPremiseRoutine' => $product_version->routine?->premise_linux_install_routine,
                    'linuxPublicRoutine' => $product_version->routine?->public_linux_install_routine,
                    'linuxPrivateRoutine' => $product_version->routine?->private_linux_install_routine,
                    'windowsUninstallPremiseRoutine' => $product_version->routine?->premise_windows_uninstall_routine,
                    'windowsUninstallPrivateRoutine' => $product_version->routine?->private_windows_uninstall_routine,
                    'windowsUninstallPublicRoutine' => $product_version->routine?->public_windows_uninstall_routine,
                    'linuxUninstallPremiseRoutine' => $product_version->routine?->premise_linux_uninstall_routine,
                    'linuxUninstallPrivateRoutine' => $product_version->routine?->private_linux_uninstall_routine,
                    'linuxUninstallPublicRoutine' => $product_version->routine?->public_linux_uninstall_routine,
                    'macUninstallPremiseRoutine' => $product_version->routine?->premise_mac_uninstall_routine,
                    'macUninstallPrivateRoutine' => $product_version->routine?->private_mac_uninstall_routine,
                    'macUninstallPublicRoutine' => $product_version->routine?->public_mac_uninstall_routine,
                ];
            });
        } else {
            $get_listing = ProductService::getNestedLayoutForProductChildrens(true, $product->id);
        }


        return response()->json([
            'pProducts' => [
                'id' => $product->id,
                'articleNumber' => $product->article_number,
                'manufacturerNumber' => $product->manufacturer_article_number,
                'isProductNameEdit' => $product->is_product_name_edit,
                "name" => $product->name,
                "status" => $product->status,
                "description" => $product->description,
                "listingPrice" => $product->listing_price,
                "discount" => strval($product->discount),
                "manufacturerDiscount" => strval($product->manufacturer_discount),
                "paymentPeriod" => $product->paymentPeriod?->id ?? null,
                "salePrice" => $product->sale_price,
                "profit" => $product->profit,
                "tax" => $product->tax,
                "executionNumber" => $product->execution_order_number,
                "maintanenceRate" => strval($product->maintenance_rate),
                "maintanencePrice" => $product->maintanence_price,
                'deleted_at' => $product->deleted_at,
                'groups' => $product->productGroup,
                'infrastructureSetting' =>$product->infrastructureSetting,
                'versions' => $product->productEloVersion,
                "rules" => $product->rules->map(function ($item) {
                    return [
                        'id' => $item->rule_id
                    ];
                }),
                "recurringPayment" => $product->recurring_payment,
                "productCategoryId" => $product->product_category_id,
                "paymentDetailsType" => $product->payment_details_type,
                "pricePerPeriod" => $product->price_per_period,
                "unit" => $product->productUnit?->id ?? null,
                "productSoftware" => isset($product?->productSoftware) ? $product->productSoftware->id : null,
                "productPrice" => isset($product?->productPrice) ? $product->productPrice->id : null,
                "hourlyRate" => $product->hourly_rate,
                "dailyRate" => $product->daily_rate,
                "quantity" => $product->quantity,
                "serviceHours" => $product->service_hours ?? null,
                'serviceDays' => $product->service_days ?? null,
                'fixedPrice' => $product->fixed_price ?? 0,
                "amsType" => $product->ams_type ?? '',
                'category' => [
                    "id" => $product?->productCategory?->id,
                    "name" => $product?->productCategory?->name,
                    "defaultUnit" => $product?->productCategory?->default_unit,
                    "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                ],
            ],
            'servicesChildrens' => $is_software ? [] : $get_listing['servicesChildrens'],
            'childProducts' => $is_software ? [] : $get_listing['childProducts'],
            'product_versions' => $versions,
            'groups' => ProductGroup::orderBy('name')
                ->get()
                ->map(fn ($group) => [
                    'id' => $group->id,
                    'name' => $group->name,
                ]),
            'versions' => EloVersion::orderBy('name')
                ->get()
                ->map(fn ($version) => [
                    'id' => $version->id,
                    'name' => $version->name,
                ])
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "status" => "required|boolean",
            "tax" => "required_unless:paymentDetailsType,=,traveling",
            "paymentDetailsType" => "required|in:software,service,pauschal,ams,cloud-software,hosting,traveling",
            "unit" => "required_unless:paymentDetailsType,=,hosting,traveling",
            "productSoftware" => "required_unless:paymentDetailsType,=,hosting,traveling",
            "productPrice" => "required_unless:paymentDetailsType,=,hosting",
            "productGroup" => "required_unless:paymentDetailsType,=,cloud-software,traveling",
        ]);


        //    dd($request->isProductNameEditable);
        if ($request->paymentDetailsType == "software" || $request->paymentDetailsType == "cloud-software") {
            $request->validate([
                "manufacturerNumber" => "required|string",
                "executionNumber" => "required|string",
                "recurringPayment" => "required|boolean",
                "paymentPeriod" => "required",
                "listingPrice" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
                "manufacturerDiscount" => "required",
                "profit" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
                "versions" => "required",
                "eloVersion" => "required_if:paymentDetailsType,=,software",
                "salePrice" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
            ]);
            if ($request->paymentDetailsType == "software") {
                $request->validate([
                    "maintanencePrice" => "required|string",
                    "maintanenceRate" => "required|string",
                ]);
            }
        } else if ($request->paymentDetailsType == "hosting") {
            $request->validate([
                "productCategoryId" => "required",
                "paymentPeriod" => "required",
            ]);
        } else if ($request->paymentDetailsType == "service") {
            $request->validate([
                "hourlyRate" => "required",
                "dailyRate" => "required",
                "quantity" => "required",
                "productCategoryId" => "required",
                "salePrice" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
            ]);
        } else if ($request->paymentDetailsType == "pauschal") {
            $request->validate([
                "quantity" => "required",
                "productCategoryId" => "required",
                "salePrice" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
            ]);
        } else if ($request->paymentDetailsType == "ams") {
            $request->validate([
                "amsType" => "required|in:contingent,fix_price",
                "hourlyRate" => "required_if:amsType,==,contingent",
                "dailyRate" => "required_if:amsType,==,contingent",
                "paymentPeriod" => "required_if:amsType,==,contingent",
                //  "serviceHours" => "required",
                //  "serviceDays" => "required",
                "productCategoryId" => "required",
                //   "salePrice" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
            ]);
        } else if ($request->paymentDetailsType == "traveling") {
            $request->validate([
                "salePrice" => "required|regex:/^-?\d+(\.\d{1,2})?$/",
            ]);
        }

        /** calling the product service to update the products */
        $data = ProductService::update($id, $request->all());
        if (!empty($request['rules'])) {
            $rulesIds = collect($request['rules'])->pluck('id')->toArray();
            ProductRule::where('product_id', $id)->delete();
            foreach ($rulesIds as $rule_id) {
                $productRule = new ProductRule();
                $productRule->rule_id = $rule_id;
                $productRule->product_id = $id;
                $productRule->save();
            }
        }
        return response()->json(['message' => 'Record updated.', 'data' => $data], 200);
    }

    /**updating files */

    public function updateFiles(Request $request)
    {
        return ProductService::updateFiles($request->all());
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }
}