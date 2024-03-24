<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemTenant extends BaseModel
{
    use HasFactory;
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];


    public function repositories()
    {
        return $this->hasMany(TenantRepository::class, 'tenant_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'customer_id');
    }

    public function system()
    {
        return $this->belongsTo(System::class, 'system_id');
    }

    public function databaseClouds()
    {
        return $this->belongsToMany(DatabaseCloud::class, 'database_cloud_tenant');
    }

    public function distributedFilesystems()
    {
        return $this->belongsToMany(DistributedFilesystem::class, 'distributed_filesystem_tenant');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }
}
