<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabaseCloud extends BaseModel
{
    use HasFactory;

    public function servers(){
        return $this->hasMany(DatabaseCloudServer::class);
    }

    /**
     * Relation logic with model systems
     */
    public function systems()
    {
        return $this->belongsToMany(System::class, 'database_cloud_system');
    }

    public function tenants()
    {
        return $this->belongsToMany(SystemTenant::class, 'database_cloud_tenant');
    }
}
