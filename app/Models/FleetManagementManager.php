<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetManagementManager extends Model
{
    protected $guarded = [
        'id', 'updated_at', 'created_at'
    ];
    use HasFactory;
}
