<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLevelAgreement extends BaseModel
{
    use HasFactory;

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public function slaInfrastructure(){
        return $this->hasMany(SlaInfrastructure::class, 'sla_id');
    }

    public function slaServiceTime(){
        return $this->hasMany(SlaServiceTime::class, 'sla_id');
    }

    public function slaLevel(){
        return $this->hasMany(SlaLevel::class, 'sla_id');
    }
}
