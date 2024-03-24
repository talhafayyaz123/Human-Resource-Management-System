<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributedFilesystemServer extends BaseModel
{
    use HasFactory;

    public function distributedFilesystem()
    {
        return $this->belongsTo(DistributedFilesystem::class, "distributed_filesystem_id");
    }
}
