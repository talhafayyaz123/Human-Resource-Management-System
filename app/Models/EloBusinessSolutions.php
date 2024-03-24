<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EloBusinessSolutions extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'install_name', 'type'];

    public function scopeFilter($query, array $filters)
    {
        return $query->when(
            $filters['search'] ?? null,
            function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            }
        );
    }

    public function tenantRepositories()
    {
        return $this->belongsToMany(TenantRepository::class, 'elo_business_solution_cloud_tenant_repositories', 'cloud_tenant_repository_id', 'elo_business_solution_id');
    }
}
