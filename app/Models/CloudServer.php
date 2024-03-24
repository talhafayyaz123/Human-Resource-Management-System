<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudServer extends BaseModel
{
    use HasFactory;

    public function systems()
    {
        return $this->belongsTo(System::class, 'system_id');
    }
}
