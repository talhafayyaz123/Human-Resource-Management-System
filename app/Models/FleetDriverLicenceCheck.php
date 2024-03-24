<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetDriverLicenceCheck extends BaseModel
{
    use HasFactory;
    protected $guarded = [
        'id', 'updated_at'
    ];
}
