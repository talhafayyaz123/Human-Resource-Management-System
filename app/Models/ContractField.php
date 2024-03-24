<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractField extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'key', 'file_name', 'value', 'attachment_id'];

    public function attachment()
    {
        return $this->belongsTo(Attachment::class, 'attachment_id');
    }

    public function contracts()
    {
        return $this->belongsToMany(ContractField::class, 'selected_contract_fields', 'contract_field_id', 'contract_id')->withPivot(['text', 'number', 'date', 'time'])->withTimestamps();;
    }
}
