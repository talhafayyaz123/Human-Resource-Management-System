<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSoftware extends BaseModel
{
    use HasFactory;

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public function versions()
    {
        $this->hasMany(EloVersion::class, "software_id");
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'software_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'software_id');
    }
}
