<?php

namespace App\Http\Controllers;

use App\Models\AffectedGroup;
use App\Models\CloudInfrastructre;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;
use App\Traits\SetGlobalNumber;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Support\Facades\DB;

class CloudInfrastructreController extends Controller
{

    use SetGlobalNumber, CustomHelper;
    public function __construct()
    {
        // $this->middleware('check.permission:cloud-infrastrucutres.list', ['only' => ['index', 'show']]);
        // $this->middleware('check.permission:cloud-infrastrucutres.create', ['only' => ['store']]);
        // $this->middleware('check.permission:cloud-infrastrucutres.edit', ['only' => ['update']]);
        // $this->middleware('check.permission:cloud-infrastrucutres.delete', ['only' => ['destroy']]);
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
        $model = new CloudInfrastructre();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $cloud_infrastructures = $model->orderBy('created_at')
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($group) => [
                'id' => $group->id,
                'name' => $group->name,
                'systemNumber' => $group->system_number,
                'type' => $group->type,
                'subType' => $group->sub_type,
                'instanceType' => $group->instance_type,
            ]);
        return response()->json([
            'data' => $cloud_infrastructures
        ]);
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
            "type" => "required|string",
            "instanceType" => "required",
            "name" => "required|string",
            "subType" => "required",
        ]);

        $global_invoice_setting = GlobalSetting::where('key', 'system')->first();

        if (empty($global_invoice_setting)) {
            $global_setting = new GlobalSetting;
            $global_setting->key = 'system';
            $global_setting->value = 1000;
            $global_setting->save();
        } else {
            $global_setting = tap(DB::table('global_settings')->where('key', 'system'))->update([
                'value' => DB::raw("value+1")
            ])->first();
        }

        $model = new CloudInfrastructre();
        $model->type = $request->type;
        $model->instance_type = $request->instanceType;
        $model->name = $request->name;
        $model->sub_type = $request->subType;

        $number = $this->globalNumber($model, 'system_cloud', 'DH', 1000);
        $model->system_number = $number;
        // $model->system_number = ('DH' . date("Y") . '-' . $model->getNextId() . '-' . $global_setting->value);


        $model->save();

        return response()->json([
            'success' => true,
            'message' => 'Record created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cloud_infrastructure = CloudInfrastructre::findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $cloud_infrastructure->id,
                'name' => $cloud_infrastructure->name,
                'systemNumber' => $cloud_infrastructure->system_number,
                'type' => $cloud_infrastructure->type,
                'subType' => $cloud_infrastructure->sub_type,
                'instanceType' => $cloud_infrastructure->instance_type,
            ]
        ]);
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
        $model = CloudInfrastructre::findOrFail($id);

        $request->validate([
            "type" => "required|string",
            "instanceType" => "required",
            "name" => "required|string",
            "subType" => "required",

        ]);

        $model->type = $request->type;
        $model->instance_type = $request->instanceType;
        $model->sub_type = $request->subType;
        $model->name = $request->name;
        $model->save();

        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $affected_group = CloudInfrastructre::findOrFail($id);
        $affected_group->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully'
        ]);
    }
}
