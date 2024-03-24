<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabaseCloudServer extends BaseModel
{
    use HasFactory;

    public function databaseCloud()
    {
        return $this->belongsTo(DatabaseCloud::class, "database_cloud_id");
    }
}
