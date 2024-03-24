<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudInfrastructureTenantRepositories extends Model
{
    use HasFactory;

    public function eloBusinessSolutions()
    {
        return  $this->belongsToMany(EloBusinessSolutions::class, 'elo_business_solution_cloud_tenant_repositories', 'cloud_tenant_repository_id', 'elo_business_solution_id');
    }
}
