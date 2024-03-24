<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDelivery extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function assetArticles()
    {
        return $this->hasMany(AssetArticle::class, 'asset_delivery_id');
    }

    public function storageLocation()
    {
        return $this->belongsTo(StorageLocation::class, 'storage_location_id');
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('model', 'like', '%' . $search . '%');
        });
    }
}
