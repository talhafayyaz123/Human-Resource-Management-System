<?php

namespace App\Services\ModifyMyData;

use App\Dto\CreateTaskDto;
use App\Models\ModifyMyData;
use App\Models\PersonalModificationProcessManager;
use App\Models\UserProfileData;
use App\Services\TaskService\TaskService;
use Carbon\Carbon;

class ModifyMyDataService
{

    public function create($data)
    {
        $modifyMyData = new ModifyMyData();
        $modifyMyData->user_id = $data['userId'];
        $modifyMyData->process = $data['process'];
        $modifyMyData->reason = @$data['reason'];
        $modifyMyData->change_request_data = $data['changeRequestData'];
        $modifyMyData->save();
        $managers = PersonalModificationProcessManager::all();
        foreach ($managers as $manager) {
            $modifyMyData->manager()->create([
                'manager_id' => $manager->manager_id,
                'changed_by' => 0,
            ]);
            $description = $modifyMyData->userProfileData?->full_name . " requested to modify the personal data";
            $task_dto = new CreateTaskDto($data['process'], $manager->user?->user_id, 'new', 'low', $description);
            (new TaskService())->createTask($modifyMyData, $task_dto);
        }
        return $modifyMyData;
    }

    public function update($modifyMyData, $data)
    {
        $modifyMyData->process = $data['process'];
        $modifyMyData->reason = @$data['reason'];
        $modifyMyData->change_request_data = $data['changeRequestData'];
        $modifyMyData->save();
        return $modifyMyData;
    }

}
