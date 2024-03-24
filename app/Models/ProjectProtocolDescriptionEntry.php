<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProtocolDescriptionEntry extends Model
{
    use HasFactory;

    public function projectProtocol()
    {
        return $this->belongsTo(ProjectProtocol::class, 'project_protocol_id');
    }
}
