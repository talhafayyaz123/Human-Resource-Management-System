<?php

namespace App\Services;

use App\Models\ContinuousImprovementProcess;
use App\Models\GlobalSetting;
use App\Models\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\UserProfileData;
use Exception;
use App\Models\ContinuousImprovementProcessApprover;
use App\Models\ExecutiveBoard;

class ContinuousImprovementProcessService
{
    /**
     * Create a new fleet car.
     *
     * @param  array  $data
     * @return \App\Models\FleetCar
     */
    public function createContinuousImprovementProcess(array $data)
    {
        $global_invoice_setting = GlobalSetting::where('key', 'cip')->first();
        if (empty($global_invoice_setting)) {
            $global_setting = new GlobalSetting;
            $global_setting->key = 'cip';
            $global_setting->value = 100000;
            $global_setting->save();
        } else {
            $global_setting = tap(DB::table('global_settings')->where('key', 'cip'))->update([
                'value' => DB::raw("value+1")
            ])->first();
        }
        $continuous_improvement_process = ContinuousImprovementProcess::create([
            ...$data,
            "date" => Carbon::parse($data["date"]),
            "request_number" => $global_setting->value
        ]);
        $this->uploadFiles($continuous_improvement_process, isset($data['files']) ? $data['files'] : []);
        $cip_manager_id = GlobalSetting::where('key', 'LIKE', 'cipManager')->first();
        $cip_manager = UserProfileData::where('id', $cip_manager_id->value)->first();
        if (!$cip_manager) {
            throw new Exception("CIP Manager is not set");
        }
        $continuous_improvement_process->approvers()->attach($cip_manager, [
            'status' => 'pending',
            'approver_type' => 'cip-manager'
        ]);
        return $continuous_improvement_process;
    }

    /**
     * Update the specified fleet car.
     *
     * @param  \App\Models\FleetCar  $fleetCar
     * @param  array  $data
     * @return \App\Models\FleetCar
     */
    public function updateContinuousImprovementProcess(ContinuousImprovementProcess $continuous_improvement_process, array $data)
    {
        $continuous_improvement_process->update([...$data, "date" => Carbon::parse($data["date"])]);
        $continuous_improvement_process->files()->delete();
        $this->uploadFiles($continuous_improvement_process, isset($data['files']) ? $data['files'] : []);
        return $continuous_improvement_process;
    }

    /**
     * Delete the specified fleet car.
     *
     * @param  \App\Models\FleetCar  $fleetCar
     * @return void
     */
    public function deleteContinuousImprovementProcess(ContinuousImprovementProcess $continuous_improvement_process)
    {
        $continuous_improvement_process->files()->delete();
        $continuous_improvement_process->delete();
    }

    /**
     * create uploaded file based on the $files array and $model
     * @param ContinuousImprovementProcess $model
     * @param array $files
     */
    public function uploadFiles($model, $files)
    {
        foreach ($files as $file) {
            $original_name = $file->getClientOriginalName();
            $size = $file->getSize();
            $file_name_to_store = $model->request_number . '__' . $original_name;
            Storage::disk('local')->putFileAs('continuousImprovementProcess/files/', $file, $file_name_to_store);
            $uploaded_file = new UploadedFile();
            $uploaded_file->storage_name = $file_name_to_store;
            $uploaded_file->viewable_name = $original_name;
            $uploaded_file->storage_size = $size;
            $uploaded_file->fileable()->associate($model);
            $uploaded_file->save();
        }
    }

    /**
     * Sets the approval status of a CIP request and if the approver_type is 'cip-manager' then generate another approval request
     * for the 'quality-management-officer' and if approved from the 'quality-management-officer' then generate approval requests
     * for the ExecutiveBoard members
     * @param array $data
     */
    public function setApprovalStatus($data)
    {
        $approver = ContinuousImprovementProcessApprover::findOrFail($data['id']);
        $approver->status = $data['status'];
        $approver->save();
        // if status is approved and 'approver_type' is a 'cip-manager' then generate request for 'quality-management-officer'
        if ($approver->status == 'approved' && $approver->approver_type == 'cip-manager') {
            $qmo_id = GlobalSetting::where('key', 'LIKE', 'qualityManagementOfficer')->first();
            $qmo = UserProfileData::where('id', $qmo_id->value)->first();
            // if qmo is not found then throw an exception
            if (!$qmo) {
                throw new Exception("Quality Management Officer has not been set");
            }
            // generate new request for quality-management-officer
            $approver->continuousImprovementProcess->approvers()->attach($qmo, [
                'status' => 'pending',
                'approver_type' => 'quality-management-officer'
            ]);
        }
        // if status is approved and 'approver_type' is a 'quality-management-officer' then generate request for ExecutiveBoard Members
        else if ($approver->status == 'approved' && $approver->approver_type == 'quality-management-officer') {
            $executive_board = UserProfileData::whereIn("id", ExecutiveBoard::pluck('user_id'))->get();
            // if executive_board is not set then throw an exception
            if (count($executive_board) == 0) {
                throw new Exception("Executive Board has not been set");
            }
            // generate new requests for executive-board
            foreach ($executive_board as $board_member) {
                $approver->continuousImprovementProcess->approvers()->attach($board_member, [
                    'status' => 'pending',
                    'approver_type' => 'executive-board'
                ]);
            }
        }
    }
}
