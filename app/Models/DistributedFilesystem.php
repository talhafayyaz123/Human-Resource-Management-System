<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributedFilesystem extends BaseModel
{
    use HasFactory;

    public function servers(){
        return $this->hasMany(DistributedFilesystemServer::class);
    }

    /**
     * Relation logic with model systems
     */
    public function systems()
    {
        return $this->belongsToMany(System::class, 'distributed_filesystem_system');
    }

    public function tenants()
    {
        return $this->belongsToMany(SystemTenant::class, 'distributed_filesystem_tenant');
    }
}
