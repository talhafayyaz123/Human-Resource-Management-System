<?php

namespace App\Services;

use App\Models\OfferRequest;
use App\Models\GlobalSetting;
use App\Models\SaleOffer;
use App\Models\OfferRequestComponent;
use App\Models\SaleOfferComponent;
use App\Helpers\GlobalSettingHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\UploadedFile;
use Carbon\Carbon;
use Exception;

class OfferRequestService
{
    /**
     * Create a new offer request.
     *
     * @param  array  $data
     * @return \App\Models\OfferRequest
     */
    public function createOfferRequest(array $data)
    {
        $global_offer_setting = GlobalSetting::where('key', 'offerRequest')->first();
        if (empty($global_offer_setting)) {
            $global_setting = new GlobalSetting();
            $global_setting->key = 'offerRequest';
            $global_setting->value = 1;
            $global_setting->save();
        } else {
            $global_setting = tap(DB::table('global_settings')->where('key', 'offerRequest'))->update([
                'value' => DB::raw('value+1')
            ])->first();
        }
        $offer_request_number = str_pad($global_setting->value, 6, '0', STR_PAD_LEFT);
        $offer_request = OfferRequest::create([...$data, 'offer_request_number' => $offer_request_number]);
        self::saveComponents($offer_request, $data);
        return $offer_request;
    }

    /**
     * Update the specified offer request.
     *
     * @param  \App\Models\OfferRequest $offer_request
     * @param  array  $data
     * @return \App\Models\OfferRequest
     */
    public function updateOfferRequest(OfferRequest $offer_request, array $data)
    {
        $offer_request->update($data);
        self::saveComponents($offer_request, $data);
        return $offer_request;
    }

    /**
     * Delete the specified offer request.
     *
     * @param  \App\Models\OfferRequest $offer_request
     * @return void
     */
    public function deleteOfferRequest(OfferRequest $offer_request)
    {
        $offer_request->delete();
    }

    /**
     * creates a new offer from offer request
     * @param OfferRequest $offer_request
     * @return JSONResponse
     */
    public function createOffer(OfferRequest $offer_request)
    {
        try {
            DB::transaction(function () use ($offer_request) {
                $model = new SaleOffer();
                $global_offer_setting = GlobalSetting::where('key', 'offer')->first();
                if (empty($global_offer_setting)) {
                    $global_setting = new GlobalSetting();
                    $global_setting->key = 'offer';
                    $global_setting->value = 76001;
                    $global_setting->save();
                } else {
                    $global_setting = tap(DB::table('global_settings')->where('key', 'offer'))->update([
                        'value' => DB::raw('value+1')
                    ])->first();
                }
                $model->sale_offer_number = (date('Y') . '-' . $global_setting->value);
                $model->remove_from_statistics = 0;
                $model->type = 'offer';
                $model->receiver_type = $offer_request->receiver_type;
                $model->offer_type = 'budget';
                $model->company_id = $offer_request->receiver_id;
                if (!$offer_request->receiver->terms_of_payment) {
                    throw new Exception('Kindly assign a default payment period to the respective customer to continue');
                }
                $model->term_id = $offer_request->receiver->terms_of_payment;
                $model->project_id = $offer_request->project_id;
                $model->grouped_by = $offer_request->grouped_by;
                $model->created_by = $offer_request->employee?->user_id;
                $model->offer_status = 'entwurf';
                $model->expiry_date = Carbon::now();
                $model->save();

                foreach ($offer_request->components as $component) {
                    SaleOfferComponent::create([...$component->toArray(), 'sale_offer_id' => $model->id]);
                }

                $model->netto_total = $model->components()->whereNull('parent_component_id')->sum('total_netto');
                $model->save();

                $content = [
                    'moduleAction' => 'createOffer',
                    'data' => [
                        'offer' => $model->toArray(),
                        'offerProducts' => $model->components?->toArray(),
                        'offerOptionalProducts' => $model->optionalComponents?->toArray(),
                    ]
                ];
                GlobalSettingHelper::sendEloAPIRequest($content, $model);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Offer created successfully'
        ], 200);
    }

    private static function saveComponents($model, $data)
    {
        $component_ids = [];
        foreach (isset($data['components']) ? $data['components'] : [] as $component) {
            $offer_request_component = OfferRequestComponent::firstOrNew(['id' => isset($component['id']) ? $component['id'] : null]);
            $offer_request_component->type = $component['type'];
            
            $offer_request_component->description = $component['description'];
            $offer_request_component->estimated_work_in_h = @$component['estimatedWork'];
            $offer_request_component->offer_request_id = $model->id;
            $offer_request_component->save();

            if ($component['type'] == 'license' && !is_array($component['licenseReport'])) {
                $original_name = $component['licenseReport']->getClientOriginalName();
                $size = $component['licenseReport']->getSize();
                $file_name_to_store =
                    $offer_request_component->id . '__' . $original_name;
                if ($offer_request_component->file && $offer_request_component->file?->storage_name != $file_name_to_store) {
                    if (Storage::exists('offerRequests/files/' . $offer_request_component->file->storage_name)) {
                        UploadedFile::find($offer_request_component->file->id)->delete();
                        Storage::delete('offerRequests/files/' . $offer_request_component->file->storage_name);
                    }
                }
                Storage::disk('local')->putFileAs('offerRequests/files/', $component['licenseReport'], $file_name_to_store);
                $uploaded_file = new UploadedFile;
                $uploaded_file->storage_name = $file_name_to_store;
                $uploaded_file->viewable_name = $original_name;
                $uploaded_file->storage_size =  $size;
                $uploaded_file->fileable()->associate($offer_request_component);
                $uploaded_file->save();
            }

            array_push($component_ids, $offer_request_component->id);
        };
        $deleted_component_ids = $model->components()->whereNotIn('id', $component_ids)->pluck('id');
        $model->components()->whereIn('id', $deleted_component_ids)->delete();
        UploadedFile::where('fileable_type', 'App\Models\OfferRequestComponent')->whereIn('fileable_id', $deleted_component_ids)->delete();
        $model->save();
    }
}