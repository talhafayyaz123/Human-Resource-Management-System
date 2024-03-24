<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\ProductStoreRequest;
use App\Models\UploadedFile;
use App\Http\Requests\ProductScriptsStoreRequest;
use App\Http\Resources\MinProductStoreRequestResource;
use App\Http\Resources\ProductStoreRequestResource;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\GlobalSetting;
use App\Models\ProductStoreRequestComments as Comment;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as staticRequest;


class ProductStoreRequestController extends Controller
{
    use CustomHelper;

    public function __construct()
    {
        $this->middleware('check.permission:product-store-request.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:product-store.create-request', ['only' => ['store']]);
        $this->middleware('check.permission:product-store.edit-request', ['only' => ['update']]);
        $this->middleware('check.permission:product-store.delete-request', ['only' => ['destroy']]);
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
        $model = new ProductStoreRequest();
        if ($sortBy && $sortOrder) {
            $model = $this->applySortingBeforePagination($model, $sortBy, $sortOrder);
        }

        if ($request->forPartnerPortal) {
            if ($request->tenantId) {
                $model = $model->where('partner_id', $request->tenantId);
            }
        } else {
            $model = $model->where('is_show_on_admin', true);

            if ($request->tenantId) {
                $model = $model->where('partner_id', $request->tenantId);
            }

        }

        $productStoreRequest = $model->filter(staticRequest::only('search'))
        ->paginate($perPage)->withQueryString();
        return response()->json([
            'productStoreRequests' => MinProductStoreRequestResource::collection($productStoreRequest),
            'links' => $productStoreRequest->links(),
            'current_page' => $productStoreRequest->currentPage(),
            'from' => $productStoreRequest->firstItem(),
            'last_page' => $productStoreRequest->lastPage(),
            'path' => request()->url(),
            'per_page' => $productStoreRequest->perPage(),
            'to' => $productStoreRequest->lastItem(),
            'total' => $productStoreRequest->total(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductScriptsStoreRequest $request)
    {

        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $global_invoice_setting = GlobalSetting::where('key', 'productStoreRequest')->first();
        if (empty($global_invoice_setting)) {
            $global_setting = new GlobalSetting;
            $global_setting->key = 'productStoreRequest';
            $global_setting->value = 1;
            $global_setting->save();
        } else {
            $global_setting = tap(DB::table('global_settings')->where('key', 'productStoreRequest'))->update([
                'value' => DB::raw("value+1")
            ])->first();
        }
        $data['item_number'] = $global_setting->value;
        $data['description'] = $request->description ?? '';
        $data['sell_price'] = $request->sellPrice ?? 0;
        $data['status'] = $request->status ?? 'Draft';
        $data['creation_date'] = date('Y-m-d', time());
        $data['is_show_on_admin'] = $request->isShowOnAdmin ?? false;
        $productStoreRequest = ProductStoreRequest::create($data);

        if ($productStoreRequest) {

            if ($request->scripts && count($request->scripts) > 0) {
                $this->saveImages($request->scripts, $productStoreRequest, 1, 0);
            }

            if ($request->documentedImages && count($request->documentedImages) > 0) {
                $this->saveImages($request->documentedImages, $productStoreRequest, 0, 1);
            }

            if ($request->productImages && count($request->productImages) > 0) {
                $this->saveImages($request->productImages, $productStoreRequest, 0, 0);
            }

            return response()->json([
                'message' => 'Record created successfully.',
                'productStoreRequest' => $productStoreRequest->id,
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong',
        ], 400);


    }

    private function saveImages($images, $productStoreRequest, $isScript, $isDocumented): void
    {
        foreach ($images as $image) {
            $originalName = $image['name'];
            $base64Decode = base64_decode($image['base64'], true);
            // Generate a unique file name
            $fileNameToStore = rand(10, 500) . '.' . time() . '.' . $originalName;

            // Save the decoded file to disk
            Storage::disk('public')->put('productStoreRequest/' . $fileNameToStore, $base64Decode);
            $uploaded_file = new UploadedFile();
            $uploaded_file->storage_name = $fileNameToStore;
            $uploaded_file->viewable_name = $originalName;
            $uploaded_file->storage_size = $image['size'];
            $uploaded_file->is_script = $isScript;
            $uploaded_file->is_documented = $isDocumented;
            $uploaded_file->fileable()->associate($productStoreRequest);
            $uploaded_file->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productStore = ProductStoreRequest::find($id);
        if ($productStore) {
            return response()->json([
                'data' => new ProductStoreRequestResource($productStore)
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
    public function update(ProductScriptsStoreRequest $request, $id)
    {
        $validated_data = $request->validated();

        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        unset($data['scripts']);
        unset($data['product_images']);
        unset($data['document_images']);

        $data['item_number'] = $request->itemNumber;
        $data['description'] = $request->description ?? '';
        $data['sell_price'] = $request->sellPrice ?? 0;
        $data['status'] = $request->status ?? 'Draft';
        $data['is_show_on_admin'] = $request->isShowOnAdmin ?? false;

        $productStoreRequest = ProductStoreRequest::updateOrCreate(['id' => $id], $data);
        if ($productStoreRequest) {
            if ($request->scripts && count($request->scripts) > 0) {

                if ($productStoreRequest->scripts->count() > 0) {

                    foreach ($productStoreRequest->scripts()->get() as $delete_file) {
                        $path = storage_path() . '/app/public/productStoreRequest/' . $delete_file['storage_name'];
                        if (file_exists($path)) {
                            UploadedFile::find($delete_file['id'])->delete();
                            unlink($path);
                        }

                    }

                }

                $this->saveImages($request->scripts, $productStoreRequest, 1, 0);
            }

            if ($request->documentedImages && count($request->documentedImages) > 0) {

                if ($productStoreRequest->documentedImages->count() > 0) {

                    foreach ($productStoreRequest->documentedImages()->get() as $delete_file) {
                        $path = storage_path() . '/app/public/productStoreRequest/' . $delete_file['storage_name'];
                        if (file_exists($path)) {
                            UploadedFile::find($delete_file['id'])->delete();
                            unlink($path);
                        }
                    }
                }

                $this->saveImages($request->documentedImages, $productStoreRequest, 0, 1);
            }

            if ($request->productImages && count($request->productImages) > 0) {

                if ($productStoreRequest->images->count() > 0) {

                    foreach ($productStoreRequest->images()->get() as $delete_file) {
                        $path = storage_path() . '/app/public/productStoreRequest/' . $delete_file['storage_name'];
                        if (file_exists($path)) {
                            UploadedFile::find($delete_file['id'])->delete();
                            unlink($path);
                        }

                    }

                }

                $this->saveImages($request->productImages, $productStoreRequest, 0, 0);
            }
            return response()->json([
                'message' => 'Record update successfully.',
                'productStoreRequest' => $productStoreRequest->id,
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
        $productStore = ProductStoreRequest::find($id);
        if ($productStore) {
            $this->deleteImages($productStore->images());
            $this->deleteImages($productStore->scripts());
            $this->deleteImages($productStore->documentedImages());
            $productStore->delete();
            return response()->json([
                'message' => 'Record deleted successfully.'
            ]);
        }
        return response()->json([
            'message' => 'Invalid id.'
        ], 400);
    }

    public function deleteImages($productStoreRequest)
    {
        if ($productStoreRequest->count() > 0) {

            foreach ($productStoreRequest->get() as $delete_file) {
                $path = storage_path() . '/app/public/productStoreRequest/' . $delete_file['storage_name'];
                if (file_exists($path)) {
                    UploadedFile::find($delete_file['id'])->delete();
                    unlink($path);
                }

            }

        }

    }

}