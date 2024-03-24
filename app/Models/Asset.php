<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('asset_name', 'like', '%' . $search . '%');
        });
    }


    public function assetArticles()
    {
        return $this->hasMany(AssetArticle::class, 'asset_id');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }

    public function file()
    {

        return $this->morphOne(UploadedFile::class, 'fileable');
    }
}
