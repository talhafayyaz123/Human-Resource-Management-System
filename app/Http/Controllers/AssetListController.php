<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Http\Requests\AssetArticleFormRequest;
use App\Http\Resources\AssetArticleResource;
use App\Models\AssetArticle;
use App\Services\AssetManagement\AssetArticleService;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;

class AssetListController extends Controller
{

    use CustomHelper;
    protected $assetListService;
    /**
     * Create a new controller instance.
     *
     * @param AssetService $assetService The asset service instance.
     */
    public function __construct(AssetArticleService $assetListService)
    {
        $this->assetListService = $assetListService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $asset_articles = AssetArticle::where('asset_id', $request->id)->get();
        return AssetArticleResource::collection($asset_articles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssetArticleFormRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $asset_article = $this->assetListService->create($data);
        $content = [
            'moduleAction' => 'createAssetList',
            'data' => [
                'asset' => $asset_article?->toArray(),
            ],
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Asset Article Form']), 'id' => $asset_article->id], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $asset_article = AssetArticle::findOrFail($id);
        return new AssetArticleResource($asset_article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AssetArticleFormRequest $request, $id)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $asset_article = AssetArticle::findOrFail($id);
        $asset_article = $this->assetListService->update($asset_article, $data);
        $content = [
            'moduleAction' => 'updateAssetList',
            'data' => [
                'asset' => $asset_article?->toArray(),
            ],
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Asset Article Form'])], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asset_article = AssetArticle::findOrFail($id);
        $content = [
            'moduleAction' => 'deleteAssetList',
            'data' => [
                'asset' => $asset_article?->toArray(),
            ],
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        $asset_article->delete();
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Asset Article'])], 200);
    }
}
