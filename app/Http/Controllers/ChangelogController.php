<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Http\Requests\ChangelogFormRequest;
use App\Http\Resources\ChangelogResource;
use App\Models\Changelog;
use App\Services\ChangelogService\ChangelogService;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Request as staticRequest;
use Illuminate\Http\Request;

class ChangelogController extends Controller
{

    use CustomHelper;
    protected $changelogService;
    /**
     * Create a new controller instance.
     *
     * @param ChangelogService $changelogService The changelog service instance.
     */
    public function __construct(ChangelogService $changelogService)
    {
        $this->changelogService = $changelogService;
        $this->middleware('check.permission:changelog.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:changelog.create', ['only' => ['store']]);
        $this->middleware('check.permission:changelog.edit', ['only' => ['update']]);
        $this->middleware('check.permission:changelog.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $changelogs = $this->changelogService->getAllChangelogs($request);
        return ChangelogResource::collection($changelogs);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $changelog = Changelog::findOrFail($id);
        return new ChangelogResource($changelog);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChangelogFormRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $changelog = $this->changelogService->storeChangelog($data);
        $content = [
            'moduleAction' => 'createChangelog',
            'data' => [
                'changelog' => $changelog?->toArray(),
            ],
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Changelog Form']), 'id' => $changelog->id], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ChangelogFormRequest $request, $id)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $changelog = $this->changelogService->updateChangelog($id, $data);
        $content = [
            'moduleAction' => 'updateChangelog',
            'data' => [
                'changelog' => $changelog?->toArray(),
            ],
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Changelog Form']), 'id' => $changelog->id], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->changelogService->deleteChangelog($id);
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Changelog Form'])], 200);
    }
}
