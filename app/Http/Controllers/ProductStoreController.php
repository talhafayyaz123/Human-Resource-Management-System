<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Resources\MinProductStoreResource;
use App\Http\Resources\ProductStoreResource;
use App\Models\GlobalSetting;
use App\Models\ProductStore;
use App\Models\UploadedFile;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductStoreController extends Controller
{
    use CustomHelper;

    public function __construct()
    {
        $this->middleware('check.permission:product-store.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:product-store.create', ['only' => ['store']]);
        $this->middleware('check.permission:product-store.edit', ['only' => ['update']]);
        $this->middleware('check.permission:product-store.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->perPage ?? 25;

        $sortBy = $request->get('sortBy');
        $sortOrder = $request->get('sortOrder');
        $model = new ProductStore();
        if ($sortBy && $sortOrder) {
            $model = $this->applySortingBeforePagination($model, $sortBy, $sortOrder);
        }

        if ($request->tenantId) {
            $model = $model->where('author_id', $request->tenantId);
        }

        if ($request->isPartner) {
            $model = $model->where('is_visible_for_partner', $request->isPartner);
        }

        if ($request->isCustomer) {
            $model = $model->where('is_visible_for_customer', $request->isCustomer);
        }

        $store = $model->orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn ($store) => [
                'id' => $store->id,
                'itemNumber' => $store->item_number,
                'productTitle' => $store->product_title,
                'sellPrice' => $store->sell_price,
                'shortDescription' => $store->short_description,
                'isVisibleToCustomer' => $store->is_visible_for_customer ? true : false,
                'isVisibleToPartner' => $store->is_visible_for_partner ? true : false,
                'rattingAvg' => $store->reviews?->avg('ratting'),
                'totalReviews' => $store->reviews?->count(),
                'authorId' => $store->author_id,
                'authorName' => $store->partner?->company_name,
                'logo' => $store->partner?->image()?->exists() ? [
                    'base64Url' => "data:" . Storage::disk('public')->mimeType('company/' . $store->partner->image->storage_name) . ";base64," . base64_encode(Storage::disk('public')->get('company/' . $store->partner->image->storage_name))
                ] : [],
                'image' => count($store->images) > 0 ?
                    [
                        'viewableName' => $store->images[0]->viewable_name,
                        'url' => $this->getImageUrl($store->images[0]),
                        'size' => $store->images[0]->storage_size,
                    ] : [],

            ]);
        return response()->json(['productStores' => $store]);


        //        $productStore = $model->paginate($perPage)->withQueryString();
        //        return response()->json([
        //            'productStores' => MinProductStoreResource::collection($productStore),
        //            'links' => $productStore->links(),
        //            'current_page' => $productStore->currentPage(),
        //            'from' => $productStore->firstItem(),
        //            'last_page' => $productStore->lastPage(),
        //            'path' => request()->url(),
        //            'per_page' => $productStore->perPage(),
        //            'to' => $productStore->lastItem(),
        //            'total' => $productStore->total(),
        //        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $global_invoice_setting = GlobalSetting::where('key', 'productStore')->first();
        if (empty($global_invoice_setting)) {
            $global_setting = new GlobalSetting;
            $global_setting->key = 'productStore';
            $global_setting->value = 1;
            $global_setting->save();
        } else {
            $global_setting = tap(DB::table('global_settings')->where('key', 'productStore'))->update([
                'value' => DB::raw("value+1")
            ])->first();
        }
        $data['item_number'] = $global_setting->value;
        $productStore = ProductStore::create($data);
        if ($productStore) {
            if ($request->products && count($request->products) > 0) {
                $productStore->products()->sync($request->products);
            }

            if ($request->productImages && count($request->productImages) > 0) {
                $this->saveImages($request->productImages, $productStore);
            }
            return response()->json([
                'message' => 'Record created successfully.',
                'productStoreId' => $productStore->id,
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong',
        ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productStore = ProductStore::find($id);
        if ($productStore) {
            return response()->json([
                'data' => new ProductStoreResource($productStore)
            ]);
        }
        return response()->json([
            'message' => 'Invalid id.'
        ], 400);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductStoreRequest $request, $id)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        unset($data['products']);
        unset($data['product_images']);
        $productStore = ProductStore::updateOrCreate(['id' => $id], $data);
        if ($productStore) {
            if ($request->products && count($request->products) > 0) {
                $productStore->products()->sync($request->products);
            }

            $productStore->images()->delete();
            if ($request->productImages && count($request->productImages) > 0) {
                $this->saveImages($request->productImages, $productStore);
            }
            return response()->json([
                'message' => 'Record update successfully.',
                'productStoreId' => $productStore->id,
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong',
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productStore = ProductStore::find($id);
        if ($productStore) {
            $productStore->delete();
            return response()->json([
                'message' => 'Record deleted successfully.'
            ]);
        }
        return response()->json([
            'message' => 'Invalid id.'
        ], 400);
    }

    private function saveImages($images, $productStore): void
    {
        foreach ($images as $image) {
            $originalName = $image['name'];
            $base64Decode = base64_decode($image['base64'], true);
            // Generate a unique file name
            $fileNameToStore = time() . '.' . $originalName;

            // Save the decoded file to disk
            Storage::disk('public')->put('productStore/' . $fileNameToStore, $base64Decode);
            $uploaded_file = new UploadedFile();
            $uploaded_file->storage_name = $fileNameToStore;
            $uploaded_file->viewable_name = $originalName;
            $uploaded_file->storage_size = $image['size'];
            $uploaded_file->fileable()->associate($productStore);
            $uploaded_file->save();
        }
    }
    private function getImageUrl($image)
    {
        $file = null;
        if (isset($image->storage_name)) {
            $file = Storage::disk('public')->get('productStore/' . $image->storage_name);
        }
        if (!$file) {
            return response()->json();
        }
        $mime = Storage::disk('public')->mimeType('productStore/' . $image->storage_name);
        $base64 = base64_encode($file);
        return "data:$mime;base64,$base64";
    }
}
