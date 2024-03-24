<?php

namespace App\Http\Controllers;


use App\Models\AssetDelivery;
use App\Services\AssetManagement\AssetArticleService;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;
use Illuminate\Support\Facades\Storage;

class AssetDeliveryController extends Controller
{
    use CustomHelper;

    public AssetArticleService $asset_article_service;

    function __construct(AssetArticleService $asset_article_service)
    {
        $this->asset_article_service = $asset_article_service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');

        $asset_deliveries = new AssetDelivery();

        if ($sort_by && $sort_order) {
            $asset_deliveries = $this->applySortingBeforePagination($asset_deliveries, $sort_by, $sort_order);
        }
        $asset_deliveries = $asset_deliveries
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(function ($asset_delivery) {
                return [
                    'id' => $asset_delivery->id,
                    'assetId' => $asset_delivery->asset_id,
                    'suppliersId' => $asset_delivery->supplier_id,
                    'deliveryNoteNumber' => $asset_delivery->delivery_note_number,
                    'invoiceNumber' => $asset_delivery->invoice_number,
                    'deliveryDate' => Carbon::parse($asset_delivery->delivery_date)->toDateString(),
                    'isDeliveryChecked' => $asset_delivery->is_delivery_checked,
                    'quantity' => $asset_delivery->quantity,
                    'storageLocationId' => $asset_delivery->storage_location_id,
                    'model' => $asset_delivery->model,
                    'createdAt' => Carbon::parse($asset_delivery->created_at)->toDateString(),
                ];
            });

        return response()->json(['asset_deliveries' => $asset_deliveries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'assetId' => 'required|exists:assets,id',
            'suppliersId' => 'required|exists:suppliers,id',
            'deliveryNoteNumber' => 'required|string',
            'invoiceNumber' => 'required|string',
            'deliveryDate' => 'required|date',
            'isDeliveryChecked' => 'required|boolean',
            'quantity' => 'required|integer|max:100',
            'storageLocationId' => 'required|exists:storage_locations,id',
            'model' => 'required|string',
            'assetArticles' => 'required|array',
            'assetArticles.*.serialNo' => 'required|string',
            'assetArticles.*.inventoryStatus' => 'required|string'
        ]);
        $asset_delivery = new AssetDelivery();
        $asset_delivery = $this->saveData($asset_delivery, $request);
        $asset_article_data['assetArticles'] = $request->assetArticles;
        $asset_article_data['assetId'] = $request->assetId;
        $asset_article_data['assetDelivery'] = $asset_delivery->id;
        $this->asset_article_service->create($asset_article_data);
        return response()->json(['message' => 'Asset delivery has been created successfully']);
    }

    private function saveData(AssetDelivery $asset_delivery, Request $request)
    {
        $asset_delivery->asset_id = $request->assetId;
        $asset_delivery->supplier_id = $request->suppliersId;
        $asset_delivery->delivery_note_number = $request->deliveryNoteNumber;
        $asset_delivery->invoice_number = $request->invoiceNumber;
        $asset_delivery->model = $request->model;
        $asset_delivery->delivery_date = Carbon::parse($request->deliveryDate);
        $asset_delivery->storage_location_id = $request->storageLocationId;
        $asset_delivery->quantity = $request->quantity;
        $asset_delivery->is_delivery_checked = $request->isDeliveryChecked;
        $asset_delivery->save();
        return $asset_delivery;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $asset_delivery = AssetDelivery::findOrFail($id);
        $asset_delivery_data = [
            'id' => $asset_delivery->id,
            'assetId' => $asset_delivery->asset_id,
            'suppliersId' => $asset_delivery->supplier_id,
            'deliveryNoteNumber' => $asset_delivery->delivery_note_number,
            'invoiceNumber' => $asset_delivery->invoice_number,
            'deliveryDate' => Carbon::parse($asset_delivery->delivery_date)->toDateString(),
            'isDeliveryChecked' => $asset_delivery->is_delivery_checked,
            'quantity' => $asset_delivery->quantity,
            'storageLocationId' => $asset_delivery->storage_location_id,
            'model' => $asset_delivery->model,
            'createdAt' => Carbon::parse($asset_delivery->created_at)->toDateString(),
            'assetArticles' => $asset_delivery->assetArticles->map(function ($article) {
                $asset = $article->asset;
                return [
                    'id' => $article->id,
                    'assetName' => $asset->asset_name,
                    'model' => $article->assetDelivery->model,
                    'serialNo' => $article->serial_no,
                    'deliveryDate' => $article->assetDelivery->delivery_date,
                    'storageLocation' => $article->assetDelivery->storage_location_id,
                    'inventoryStatus' => $article->inventory_status,
                    'assetId' => $article->asset_id,
                    'assetImage' => $asset->file()->exists() ? [
                        'name' => $asset->file->viewable_name, 'size' => $asset->file->storage_size,
                        'base64' => base64_encode(Storage::disk('public')->get('public/assets/files/' . $asset->file->storage_name)),
                    ] : [],
                    'assetListId' => $article->asset_list_id
                ];
            })
        ];
        return response()->json(['data' => $asset_delivery_data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'assetId' => 'required|exists:assets,id',
            'suppliersId' => 'required|exists:suppliers,id',
            'deliveryNoteNumber' => 'required|string',
            'invoiceNumber' => 'required|string',
            'deliveryDate' => 'required|date',
            'isDeliveryChecked' => 'required|boolean',
            'quantity' => 'required|integer|max:100',
            'storageLocationId' => 'required|exists:storage_locations,id',
            'model' => 'required|string',
            'assetArticles' => 'required|array',
            'assetArticles.*.serialNo' => 'required|string',
            'assetArticles.*.inventoryStatus' => 'required|string'
        ]);
        $asset_delivery = AssetDelivery::findOrFail($id);
        $asset_delivery = $this->saveData($asset_delivery, $request);
        return response()->json(['message' => 'Asset delivery has been updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asset_delivery = AssetDelivery::findOrFail($id);
        $asset_delivery->delete();
        return response()->json(['message' => 'Asset delivery has been updated successfully']);
    }
}
