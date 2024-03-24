<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends BaseModel
{
    use HasFactory;
    /**
     * Apply search filters to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters An array containing the search filter parameters.
     *                       The 'search' key should hold the value to search for.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('region', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%');
        });
    }

    public function companyLocation()
    {
        $this->hasOne(CompanyLocation::class, 'country_id');
    }
}
