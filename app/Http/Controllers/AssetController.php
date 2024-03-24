<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Http\Requests\AssetFormRequest;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use App\Models\FleetDriver;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use App\Services\AssetManagement\AssetService;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as staticRequest;

class AssetController extends Controller
{

    protected $assetService;

    use CustomHelper;

    /**
     * Create a new controller instance.
     *
     * @param AssetService $assetService The asset service instance.
     */
    public function __construct(AssetService $assetService)
    {
        $this->middleware('check.permission:asset.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:asset.create', ['only' => ['store']]);
        $this->middleware('check.permission:asset.edit', ['only' => ['update']]);
        $this->middleware('check.permission:asset.delete', ['only' => ['destroy']]);
        $this->assetService = $assetService;
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
        $model = new Asset();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }

        $assets = $model->filter(staticRequest::only('search'))->paginate($per_page);

        return AssetResource::collection($assets);
   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssetFormRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $global_invoice_setting = GlobalSetting::where('key', 'asset')->first();
        if (empty($global_invoice_setting)) {
            $global_setting = new GlobalSetting;
            $global_setting->key = 'asset';
            $global_setting->value = 1000;
            $global_setting->save();
        } else {
            $global_setting = tap(DB::table('global_settings')->where('key', 'asset'))->update([
                'value' => DB::raw("value+1")
            ])->first();
        }
        $data['asset_number'] = 'AS-' . $global_setting->value;
        $asset = $this->assetService->storeAsset($data);
        $content = [
            'moduleAction' => 'createAsset',
            'data' => [
                'asset' => $asset?->toArray(),
            ],
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $asset);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Asset Form']), 'id' => $asset->id], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $asset = Asset::findOrFail($id)->load('file');
        $request['modelName'] = class_basename($asset);
        return new AssetResource($asset);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AssetFormRequest $request, $id)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $asset = $this->assetService->updateAsset($id, $data);
        $content = [
            'moduleAction' => 'updateAsset',
            'data' => [
                'asset' => $asset?->toArray(),
            ],
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $asset);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Asset Form']), 'id' => $asset->id], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->assetService->deleteAsset($id);
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Asset Form'])], 200);
    }
}
