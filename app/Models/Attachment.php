<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name', 'software_id', 'template', 'contract_type_id', 'prefix', 'version', 'allow_to_add_slas',
        'runtime_in', 'renewal_period', 'renewal_period_in', 'contract_end_at', 'notice_period_in', 'right_of_termination', 'is_select_user', 'add_products_to_customer_licences'
    ];

    public function software()
    {
        return $this->belongsTo(ProductSoftware::class, 'software_id');
    }

    public function ProductSoftware()
    {
        return $this->belongsTo(ProductSoftware::class, 'software_id');
    }

    public function contractType()
    {
        return $this->belongsTo(ContractType::class, 'contract_type_id');
    }

    public function contractFields()
    {
        return $this->hasMany(ContractField::class, 'attachment_id');
    }

    public function contracts()
    {
        return $this->belongsToMany(OutboundedContract::class, 'contract_attachment', 'attachment_id', 'contract_id')->withTimestamps();
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')->orWhereHas('software', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('contractType', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhere('prefix', 'like', '%' . $search . '%')->orWhere('version', 'like', '%' . $search . '%');
        });
    }
}
