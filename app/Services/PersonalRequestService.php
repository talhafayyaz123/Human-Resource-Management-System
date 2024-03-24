<?php

namespace App\Services;

use App\Models\PersonalRequest;
use App\Models\TimeModel;
use App\Models\PersonalRequestApprover;
use Carbon\Carbon;
use Exception;

class PersonalRequestService
{
    /**
     * Create a new fleet car.
     *
     * @param  array  $data
     * @return \App\Models\PersonalRequest
     */
    public function createPersonalRequest(array $data)
    {
        $personal_request = PersonalRequest::create([...$data, 'start_date' => Carbon::parse($data['start_date'])]);
        $this->saveTimeModel($personal_request, $data);
        $manager = PersonalRequestApprover::where('type', 'manager')->first();
        if (!$manager) {
            throw new Exception('Personal request manager is not set');
        }
        $personal_request->personalRequirements()->create([
            'status' => 'pending',
            'approver_id' => $manager->approver_id
        ]);
        return $personal_request;
    }

    /**
     * Update the specified personal_request.
     *
     * @param  \App\Models\PersonalRequest  $personal_request
     * @param  array  $data
     * @return \App\Models\PersonalRequest
     */
    public function updatePersonalRequest(PersonalRequest $personal_request, array $data)
    {
        $personal_request->update([...$data, 'start_date' => Carbon::parse($data['start_date'])]);
        $this->saveTimeModel($personal_request, $data);
        return $personal_request;
    }

    /**
     * Delete the specified personal_request.
     *
     * @param  \App\Models\PersonalRequest  $personal_request
     * @return void
     */
    public function deletePersonalRequest(PersonalRequest $personal_request)
    {
        $personal_request->delete();
    }

    private function saveTimeModel(PersonalRequest $personal_request, array $data)
    {
        if (isset($data['time_model'])) {
            $time_model = isset($data['time_model']) ? $data['time_model'] : [];
            // Create an array of unique day values from the workHoursArray
            $days = collect($time_model)->pluck('day')->unique();

            // Update or create UserWorkingHour records
            foreach ($time_model as $data) {
                if (isset($data['day']) && isset($data['numOfHours'])) {
                    TimeModel::updateOrCreate(
                        ['personal_request_id' => $personal_request->id, 'day' => $data['day']],
                        ['working_hours' => $data['numOfHours']]
                    );
                }
            }

            // Delete UserWorkingHour records where day is not in the workHoursArray
            TimeModel::where('personal_request_id', $personal_request->id)
                ->whereNotIn('day', $days)
                ->delete();
        }
    }
}
