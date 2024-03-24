<?php

namespace App\Http\Controllers;

use App\Models\StorageLocation;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;

class StorageLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use CustomHelper;

    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $storages = new StorageLocation();
        if ($sort_by && $sort_order) {
            $storages = $this->applySortingBeforePagination($storages, $sort_by, $sort_order);
        }
        $storages = $storages->orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($storage) => [
                'id' => $storage->id,
                'storageLocation' => $storage->storage_location,
                'createdAt' => Carbon::parse($storage->created_at)->toDateString(),
            ]);
        return response()->json(['storages' => $storages]);
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
            "storageLocation" => "required|string",
        ]);
        $model = new StorageLocation();
        $model->storage_location = $request->storageLocation;
        $model->save();
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Storage Location'])], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $storage = StorageLocation::where("id", $id)->first();
        return response()->json(['storage' => [
            'id' => $storage->id,
            "storageLocation" => $storage->storage_location,
        ]]);
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
            "storageLocation" => "required|string",
        ]);
        $model = StorageLocation::findOrFail($id);
        $model->storage_location = $request->storageLocation;
        $model->save();
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = StorageLocation::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }
}
