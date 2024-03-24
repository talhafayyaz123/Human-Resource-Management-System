<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelationRequest extends Model
{
    use HasFactory;

    protected $fillable=['customer_id','partner_id','reason'];
    protected $table='cancelation_requests';
    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->orWhere('reason', 'like', '%' . $search . '%');
        });
    }

    public function entries()
    {
        return $this->belongsToMany(ProductStore::class,'cancel_request_store_entry','cancelation_request_id','store_entry_id');
    }

    public function partner()
    {
        return $this->belongsTo(Company::class, 'partner_id');
    }
    
    public function customer()
    {
        return $this->belongsTo(Company::class, 'customer_id');
    }
}
