<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetManagementIntervalSetting extends BaseModel
{
    protected $guarded = [
        'id', 'updated_at', 'created_at'
    ];
    use HasFactory;

    public function managers()
    {
        return $this->hasMany(FleetManagementManager::class, 'interval_setting_id');
    }
}
