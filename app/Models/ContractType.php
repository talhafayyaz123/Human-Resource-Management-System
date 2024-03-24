<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractType extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name'];

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'contract_type_id');
    }
}
