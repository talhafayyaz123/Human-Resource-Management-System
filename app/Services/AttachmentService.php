<?php

namespace App\Services;

use App\Models\Attachment;
use Carbon\Carbon;

class AttachmentService
{
    /**
     * Create a new fleet car.
     *
     * @param  array  $data
     * @return \App\Models\FleetCar
     */
    public function createAttachment(array $data)
    {
        $attachment = Attachment::create([
            ...$data,
            "contract_end_at" => isset($data['contract_end_at']) ?  Carbon::parse($data['contract_end_at']) : null,
            "is_select_user" => $data['select_user'] ?? false,
            'add_products_to_customer_licences' => $data['add_products_to_customer_licenses'] ?? false
        ]);
        $contract_fields = isset($data['contract_fields']) ? $data['contract_fields'] : [];
        $attachment->contractFields()->createMany($contract_fields);
        return $attachment;
    }

    /**
     * Update the specified fleet car.
     *
     * @param  \App\Models\FleetCar  $fleetCar
     * @param  array  $data
     * @return \App\Models\FleetCar
     */
    public function UpdateAttachment(Attachment $attachment, array $data)
    {


        $attachment->update([
            ...$data,
            "contract_end_at" => isset($data['contract_end_at']) ?  Carbon::parse($data['contract_end_at']) : null,
            "is_select_user" => $data['select_user'] ?? false,
            'add_products_to_customer_licences' => $data['add_products_to_customer_licenses'] ?? false
        ]);

        $contract_fields = isset($data['contract_fields']) ? $data['contract_fields'] : [];
        $attachment->contractFields()->delete();
        $attachment->contractFields()->createMany($contract_fields);
        return $attachment;
    }

    /**
     * Delete the specified fleet car.
     *
     * @param  \App\Models\FleetCar  $fleetCar
     * @return void
     */
    public function deleteAttachment(Attachment $attachment)
    {
        $attachment->delete();
    }
}
