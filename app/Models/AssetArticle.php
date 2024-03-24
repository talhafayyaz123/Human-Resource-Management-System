<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetArticle extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('model', 'like', '%' . $search . '%');
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('inventory_status', $status);
        });
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function assetDelivery()
    {
        return $this->belongsTo(AssetDelivery::class, 'asset_delivery_id');
    }

    public function employeeList()
    {
        return $this->belongsTo(AssetList::class, 'asset_list_id');
    }
}
