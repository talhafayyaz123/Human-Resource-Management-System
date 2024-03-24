<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetList extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function employee()
    {
        return $this->belongsTo(UserProfileData::class, 'employee_id');
    }

    public function userProfileData()
    {
        return $this->belongsTo(UserProfileData::class, 'employee_id');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }

    public function assetArticles()
    {
        return $this->hasMany(AssetArticle::class, 'asset_list_id');
    }

    public function assetEmployeeTexts()
    {
        return $this->belongsToMany(AssetEmployeeListText::class, 'asset_list_employee_texts', 'asset_list_id', 'employee_text_id');
    }
}
