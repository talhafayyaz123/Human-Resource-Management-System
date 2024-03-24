<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudInfrastructureTenants extends Model
{
    use HasFactory;
    protected $fillable = ['system_id', 'partner_id', 'customer_id', 'tenant_name'];

    public function cloudTenantRepositories()
    {
        return $this->hasMany(CloudInfrastructureTenantRepositories::class, 'cloud_tenant_id');
    }

    public function partner()
    {
        return $this->belongsTo(Company::class, 'partner_id');
    }

    public function customer()
    {
        return $this->belongsTo(Company::class, 'customer_id');
    }

    public function cloudInfrastructure()
    {
        return $this->belongsTo(CloudInfrastructre::class, 'system_id');
    }
}
