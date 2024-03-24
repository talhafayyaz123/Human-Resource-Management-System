<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends BaseModel
{
    use HasFactory;

    public function projectProtocols(){
        return $this->hasMany(ProjectProtocol::class, 'protocol_type_id');
    }
}
