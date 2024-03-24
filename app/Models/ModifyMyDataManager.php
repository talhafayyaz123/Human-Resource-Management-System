<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModifyMyDataManager extends BaseModel
{
    use HasFactory;

    protected $fillable = ['manager_id', 'changed_by'];

    public function employee()
    {
        return $this->belongsTo(UserProfileData::class, 'manager_id');
    }

    public function modifyMyData()
    {
        return $this->belongsTo(ModifyMyData::class, 'modify_my_data_id');
    }
}
