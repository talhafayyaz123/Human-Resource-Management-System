<?php

namespace App\Services\FleetManagement;

use App\Models\FleetCar;
use App\Models\FleetDriver;
use App\Models\UploadedFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class FleetCarsService
{
    /**
     * Create a new fleet car.
     *
     * @param  array  $data
     * @return \App\Models\FleetCar
     */
    public function createFleetCar(array $data)
    {
        if (isset($data['driver_id'])) {
            $fleet_driver = FleetDriver::firstOrCreate(['user_id' => $data['driver_id']]);
        }

        $fleet_car = FleetCar::create($data);
        if (isset($fleet_driver->id))
            $fleet_car->fleetDriver()->attach($fleet_driver->id);
        return $fleet_car;
    }

    /**
     * Update the specified fleet car.
     *
     * @param  \App\Models\FleetCar  $fleetCar
     * @param  array  $data
     * @return \App\Models\FleetCar
     */
    public function updateFleetCar(FleetCar $fleetCar, array $data)
    {
        if (isset($data['driver_id'])) {
            $fleet_driver = FleetDriver::firstOrCreate(['user_id' => $data['driver_id']]);
        }
        $fleetCar->update($data);

        if ($fleetCar->fleetDriver->isEmpty()) {
            if (isset($fleet_driver->id)) {
                $fleetCar->fleetDriver()->attach($fleet_driver->id);
            }
        } else if (!$fleetCar->fleetDriver()->wherePivot('fleet_driver_id', $fleet_driver->id)->exists()) {
            $fleetCar->fleetDriver()->update(['deleted_at' => Carbon::now()]);
            $fleetCar->fleetDriver()->attach($fleet_driver->id);
        }
        return $fleetCar;
    }

    /**
     * Delete the specified fleet car.
     *
     * @param  \App\Models\FleetCar  $fleetCar
     * @return void
     */
    public function deleteFleetCar(FleetCar $fleetCar)
    {
        $fleetCar->delete();
    }
    /**
     * Upload documents.
     *
     * @param  \App\Models\FleetCar  $fleetCar
     * @return void
     */
    public function uploadFiles($fleetCar, array $files)
    {
        foreach ($files as $file) {
            $original_name = $file->getClientOriginalName();
            $size = $file->getSize();
            $file_name_to_store = $fleetCar->licence_number . '__' . $original_name;
            Storage::putFileAs('fleetDocuments/files/', $file, $file_name_to_store);
            $uploaded_file = new UploadedFile();
            $uploaded_file->storage_name = $file_name_to_store;
            $uploaded_file->viewable_name = $original_name;
            $uploaded_file->storage_size = $size;
            $uploaded_file->fileable()->associate($fleetCar);
            $uploaded_file->save();
        }
    }

    public function updateUploadedFiles(FleetCar $fleetCar, $files)
    {
        $new_files_collection = collect($files);
        $files_to_be_deleted = $fleetCar->files->whereNotIn('storage_name', $new_files_collection->pluck('storage_name'));
        $files_to_be_added = $new_files_collection->whereNotIn('storage_name', $fleetCar->files->pluck('storage_name'));
        if (!empty($files_to_be_deleted)) {
            foreach ($files_to_be_deleted as $delete_file) {
                if (Storage::exists('fleetDocuments/files/' . $delete_file['storage_name'])) {
                    UploadedFile::find($delete_file['id'])->delete();
                    Storage::delete('fleetDocuments/files/' . $delete_file['storage_name']);
                }
            }
        }
        if (!empty($files_to_be_added)) {
            $this->uploadFiles($fleetCar, $files_to_be_added->toArray());
        }
    }
}
