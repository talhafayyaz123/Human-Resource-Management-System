<?php

namespace App\Services\TaskService;

use App\Dto\CreateTaskDto;
use App\Models\FleetCar;
use App\Models\FleetDriver;
use App\Models\FleetManagementIntervalSetting;
use App\Models\FuelReceipt;
use App\Models\Task;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class TaskService
{
    public function createTask($model, CreateTaskDto $createTaskDto): Task
    {
        $task = new Task();
        $task->name = $createTaskDto->name;
        $task->status = $createTaskDto->status;
        $task->employee_id = $createTaskDto->employee_id;
        $task->priority = $createTaskDto->priority;
        $task->description = $createTaskDto->description;
        $task->resubmit_on = $createTaskDto->resubmit_on;
        $task->task_id = $createTaskDto->task_id;
        $model->taskable()->save($task);
        return $task;
    }


    public function createTaskForDriverLicenceCheck(Request $request): void
    {
        $token = $request->bearerToken();
        $config = config('services.users');
        $token = $request->bearerToken();
        $url = $config['vite_auth_service_url'];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($url . '/list-users', [
            'limit_start' => 0,
            'limit_count' => 25,
            'type' => 'employee'
        ]);
        $response = $response->json();
        $users = collect($response['data'] ?? []);
        $drivers = FleetDriver::get();
        $fleet_management_interval_setting = FleetManagementIntervalSetting::where('interval_setting_type', 'licence')->first();
        if (isset($fleet_management_interval_setting->months)) {
            foreach ($drivers as $driver) {
                $driver_created_at_date = $driver->driver_licence_task_date ?? $driver->created_at;
                $driver_created_at_parse_date = Carbon::parse($driver_created_at_date);
                $driver_last_check = $driver->driverLicenceChecks->last();
                if ($driver_created_at_parse_date->diffInMonths() >= $fleet_management_interval_setting->months) {
                    if (isset($driver_last_check)) {
                        $driver_last_check_date = Carbon::parse($driver_last_check->created_at);
                        if ($driver_last_check_date->diffInMonths() >= $fleet_management_interval_setting->months) {
                            $this->mapManagersForTaskCreation($users, $fleet_management_interval_setting, $driver);
                        }
                    } else {
                        $this->mapManagersForTaskCreation($users, $fleet_management_interval_setting, $driver);
                    }
                }
            }
        }
    }

    private function mapManagersForTaskCreation(
        Collection $users,
        FleetManagementIntervalSetting $fleet_management_interval_setting,
        FleetDriver $fleet_driver
    ): void {
        $managers = $fleet_management_interval_setting->managers;
        $managers->map(function ($manager) use ($users, $fleet_driver) {
            $driver = $users->firstWhere('id', $fleet_driver->user_id);
            $name = 'Pending licence check for ' . $driver['first_name'];
            $create_task_dto = new CreateTaskDto($name, $manager->manager_id);
            $this->createTask($fleet_driver, $create_task_dto);
            $fleet_driver->update(['driver_licence_task_date' => Carbon::now()]);
        });
    }


    public function createTaskBeforeEndOfLease(Request $request)
    {
        $fleet_cars = FleetCar::get();
        $fleet_management_interval_setting = FleetManagementIntervalSetting::first();
        $managers = $fleet_management_interval_setting?->managers;
        if (isset($managers)) {
            $fleet_cars->map(function ($fleet_car) use ($managers) {
                if (!$fleet_car->is_task_created) {
                    if ($fleet_car->leasing_end_date >= Carbon::now()) {
                        $managers->map(function ($manager) use ($fleet_car) {
                            $name = 'Leasing date for car ' . $fleet_car->licence_number . ' is ending';
                            $create_task_dto = new CreateTaskDto($name, $manager->manager_id);
                            $this->createTask($fleet_car, $create_task_dto);
                            $fleet_car->update(['is_task_created' => true]);
                        });
                    }
                }
            });
        }
    }

    public function createTaskForFuelMonitoring(Request $request)
    {

        $fleet_management_interval_setting = FleetManagementIntervalSetting::where('interval_setting_type', 'fuel')->first();
        if (!empty($fleet_management_interval_setting)) {
            $managers = $fleet_management_interval_setting?->managers;
            $months = Carbon::now()->subMonths($fleet_management_interval_setting->months);
            $fuel_receipt = FuelReceipt::where('created_at', '<', $months)
                ->where('is_task_created', false)->get();
            if (isset($managers)) {
                $fuel_receipt->map(function ($fuel_receipt) use ($managers) {
                    $managers->map(function ($manager) use ($fuel_receipt) {
                        $fleet_car = $fuel_receipt->fleetCar;
                        $name = 'Fuel Receipt for car ' . $fleet_car->licence_number . ' is due';
                        $create_task_dto = new CreateTaskDto($name, $manager->manager_id);
                        $this->createTask($fleet_car, $create_task_dto);
                        $fuel_receipt->update(['is_task_created' => true]);
                    });
                });
            }
        }
    }
}
