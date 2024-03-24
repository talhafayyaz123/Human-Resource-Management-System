<?php

namespace App\Http\Controllers;

use App\Models\ServiceLevelAgreement;
use App\Models\SlaInfrastructure;
use App\Models\SlaLevel;
use App\Models\SlaServiceTime;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;

class ServiceLevelAgreementController extends Controller
{
    use CustomHelper;
    /**
     * Runs when instance is initiated
     */
    public function __construct()
    {
        $this->middleware('check.permission:service-level-agreement.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:service-level-agreement.create', ['only' => ['store']]);
        $this->middleware('check.permission:service-level-agreement.edit', ['only' => ['update']]);
        $this->middleware('check.permission:service-level-agreement.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $serviceLevelAgreements = ServiceLevelAgreement::orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($serviceLevelAgreement) => [
                'id' => $serviceLevelAgreement->id,
                'name' => $serviceLevelAgreement->name,
                'createdAt' => Carbon::parse($serviceLevelAgreement->created_at)->format('d/m/y'),
            ]);
        return response()->json(['serviceLevelAgreements' => $serviceLevelAgreements]);
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
            "name" => "required|string",
            "slaInfrastructure" => 'nullable|array',
            "slaInfrastructure.*.infrastructureName" => 'required',
            "slaInfrastructure.*.access" => 'required|boolean',
            "slaInfrastructure.*.includedUsers" => 'required',
            "slaInfrastructure.*.additionalUser" => 'nullable',
            "slaService" => 'nullable|array',
            "slaService.*.serviceName" => 'required',
            "slaService.*.days" => 'required',
            "slaService.*.from" => 'required',
            "slaService.*.to" => 'required',
            "slaLevel" => 'nullable|array',
            "slaLevel.*.slaName" => 'required',
            "slaLevel.*.levels.*.priority" => 'required',
            "slaLevel.*.levels.*.incident" => 'required',
            "slaLevel.*.levels.*.change" => 'required',
        ], [
            'slaInfrastructure.*.infrastructureName.required' => 'The sla Infrastructure name field is required',
            'slaInfrastructure.*.access.required' => 'The sla Infrastructure access field is required',
            'slaInfrastructure.*.access.boolean' => 'The sla Infrastructure access field must be true or false',
            'slaInfrastructure.*.includedUsers.required' => 'The sla Infrastructure user included field is required',
            'slaService.*.serviceName.required' => 'The sla service times name fields is required',
            'slaService.*.days.required' => 'The sla service times days field is required',
            'slaService.*.from.required' => 'The sla service times from field is required',
            'slaService.*.to.required' => 'The sla service to field is required',
            'slaLevel.*.slaName.required' => 'The sla service name field is required',
            'slaLevel.*.levels.*.priority.required' => 'The sla level priority field is required',
            'slaLevel.*.levels.*.incident.required' => 'The sla level incident field is required',
            'slaLevel.*.levels.*.change.required' => 'The sla level change field is required',
        ]);
        //Create Service Level Agreement
        $model = new ServiceLevelAgreement;
        $model->name = $request->name;
        $model->save();
        $this->saveData($model, $request, 'true');
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Service Level Agreement'])], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $serviceLevelAgreement = ServiceLevelAgreement::with(
            'slaInfrastructure',
            'slaLevel',
            'slaServiceTime'
        )->where("id", $id)->first();
        $data = [
            'id' => $serviceLevelAgreement->id,
            "name" => $serviceLevelAgreement->name,
            "slaInfrastructure" => $serviceLevelAgreement->slaInfrastructure->map(function ($item) {
                return [
                    'id' => $item->id,
                    'infrastructureName' => $item->name,
                    'access' => $item->is_access == 1 ? true : false,
                    'includedUsers' => $item->user_included,
                    'additionalUser' => $item->additional_user_cost,
                ];
            }),
            'slaService' => $serviceLevelAgreement->slaServiceTime->map(function ($serviceTimeItem) {
                return [
                    'id' => $serviceTimeItem->id,
                    'slaInfrastructureId' => $serviceTimeItem->sla_infrastructure_id,
                    'serviceName' => $serviceTimeItem->slaInfrastructure?->name,
                    'days' => $serviceTimeItem->days,
                    'from' => $serviceTimeItem->from,
                    'to' => $serviceTimeItem->to,
                ];
            }),
        ];
        $slaLevels = $serviceLevelAgreement->slaLevel->pluck('slaInfrastructure.name', 'sla_infrastructure_id')
            ->unique('id')->toArray();
        $slaLevelData = [];
        foreach ($slaLevels as $key => $slaLevel) {
            $levels = $serviceLevelAgreement->slaLevel->where('sla_infrastructure_id', $key);
            $slaLevelData[] = [
                'slaName' => $slaLevel,
                'levels' => $levels->map(function ($item) {
                    return [
                        'priority' => $item->priority,
                        'incident' => $item->incident,
                        'change' => $item->change,
                    ];
                }),
            ];
        }
        $data['slaLevel'] = $slaLevelData;
        return response()->json(['serviceLevelAgreement' => $data]);
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
            "name" => "required|string",
            "slaInfrastructure" => 'nullable|array',
            "slaInfrastructure.*.infrastructureName" => 'required',
            "slaInfrastructure.*.access" => 'required|boolean',
            "slaInfrastructure.*.includedUsers" => 'required',
            "slaInfrastructure.*.additionalUser" => 'nullable',
            "slaService" => 'nullable|array',
            "slaService.*.serviceName" => 'required',
            "slaService.*.days" => 'required',
            "slaService.*.from" => 'required',
            "slaService.*.to" => 'required',
            "slaLevel" => 'nullable|array',
            "slaLevel.*.slaName" => 'required',
            "slaLevel.*.levels.*.priority" => 'required',
            "slaLevel.*.levels.*.incident" => 'required',
            "slaLevel.*.levels.*.change" => 'required',
        ], [
            'slaInfrastructure.*.infrastructureName.required' => 'The sla Infrastructure name field is required',
            'slaInfrastructure.*.access.required' => 'The sla Infrastructure access field is required',
            'slaInfrastructure.*.access.boolean' => 'The sla Infrastructure access field must be true or false',
            'slaInfrastructure.*.includedUsers.required' => 'The sla Infrastructure user included field is required',
            'slaService.*.serviceName.required' => 'The sla service times name fields is required',
            'slaService.*.days.required' => 'The sla service times days field is required',
            'slaService.*.from.required' => 'The sla service times from field is required',
            'slaService.*.to.required' => 'The sla service to field is required',
            'slaLevel.*.slaName.required' => 'The sla service name field is required',
            'slaLevel.*.levels.*.priority.required' => 'The sla level priority field is required',
            'slaLevel.*.levels.*.incident.required' => 'The sla level incident field is required',
            'slaLevel.*.levels.*.change.required' => 'The sla level change field is required',
        ]);

        $model = ServiceLevelAgreement::where(["id" => $id])->first();
        $model->name = $request->name;
        $model->save();
        $this->saveData($model, $request);
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
        ServiceLevelAgreement::where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }

    private function saveData($model, $request, $isNew = false)
    {
        if (!$isNew) {
            $model->slaLevel()->delete();
            $model->slaServiceTime()->delete();
            $model->slaInfrastructure()->delete();
        }
        foreach ($request->slaInfrastructure as $slaInfrastructure) {
            $data = [
                'name' => $slaInfrastructure['infrastructureName'],
                'is_access' => $slaInfrastructure['access'] ? 1 : 0,
                'user_included' => $slaInfrastructure['includedUsers'],
                'additional_user_cost' => $slaInfrastructure['additionalUser'],
            ];
            $data['sla_id'] = $model->id;
            SlaInfrastructure::create($data);
        }

        foreach ($request->slaService as $slaService) {
            $slaInfrastructure = SlaInfrastructure::where('sla_id', $model->id)->where('name', $slaService['serviceName'])->first();
            $data = [
                'sla_id' => $model->id,
                'sla_infrastructure_id' => $slaInfrastructure->id,
                'from' => $slaService['from'],
                'to' => $slaService['to'],
                'days' => $slaService['days'],
            ];
            SlaServiceTime::create($data);
        }

        foreach ($request->slaLevel as $slaLevel) {
            $slaInfrastructure = SlaInfrastructure::where('sla_id', $model->id)->where('name', $slaLevel['slaName'])->first();
            foreach ($slaLevel['levels'] as $entry) {
                $entry['sla_infrastructure_id'] = $slaInfrastructure->id;
                $entry['sla_id'] = $model->id;
                SlaLevel::create($entry);
            }
        }
    }
}
