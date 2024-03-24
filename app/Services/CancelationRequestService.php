<?php

namespace App\Services;

use App\Models\CancelationRequest;
use App\Models\ProductCategory;
use App\Models\ProductConfiguration;
use App\Models\ProductGroup;
use App\Models\ProductInstallation;
use App\Models\ProductParameter;
use App\Models\ProductRoutine;
use App\Models\ProductServiceChildren;
use App\Models\ProductServiceSoftwareChildren;
use App\Models\ProductSoftwareServiceChildren;
use App\Models\ProductVersion;
use App\Models\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\NestedArrayTraits;
use App\Traits\SetGlobalNumber;


class CancelationRequestService
{
    use NestedArrayTraits, SetGlobalNumber;

    public function create(array $product_array)
    {
        $model = new CancelationRequest;
        DB::transaction(function () use ($product_array, $model) {
          
            $model->customer_id = $product_array['customer_id'];
            $model->partner_id = $product_array['partner_id'];
            $model->reason = $product_array['reason'];
            $model->save();
            if (isset($product_array['store_entry_id'])) {
            $model->entries()->attach($product_array['store_entry_id']);
            }
            
        });
        return ["id" => $model->id];
    }

}
