<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractFieldProduct extends BaseModel
{
    use HasFactory;

    public $incrementing = true;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function slaServiceTime()
    {
        return $this->belongsTo(SlaServiceTime::class, 'sla_service_time_id');
    }

    public function slaLevel()
    {
        return $this->belongsTo(SlaLevel::class, 'sla_level_id');
    }

    public function slaLevelIncident()
    {
        return $this->belongsTo(SlaLevelIncident::class, 'sla_level_incident_id');
    }

    public function slaLevelChange()
    {
        return $this->belongsTo(SlaLevelChange::class, 'sla_level_change_id');
    }
}
