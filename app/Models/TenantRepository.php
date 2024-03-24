<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantRepository extends BaseModel
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];
    use HasFactory;

    public function tenant()
    {
        return $this->belongsTo(SystemTenant::class, 'tenant_id');
    }
}
