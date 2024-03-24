<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPortalNews extends BaseModel
{
    use HasFactory;

    protected $table = "customer_portal_news";

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('subject', 'like', '%' . $search . '%');
        });
    }
}
