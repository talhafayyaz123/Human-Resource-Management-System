<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProtocol extends BaseModel
{
    use HasFactory;

    protected $casts = [
        "distributors" => "array",
        "participants" => "array"
    ];

    protected $fillable = ['date', 'recorder_id', 'customer_id', 'distributors', 'participants', 'protocol_type_id', 'project_id', 'project_status_id'];

    public function customer()
    {
        return $this->belongsTo(Company::class, 'customer_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'customer_id');
    }

    public function protocolType()
    {
        return $this->belongsTo(ProtocolType::class, 'protocol_type_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function projectStatus()
    {
        return $this->belongsTo(ProjectStatus::class, 'project_status_id');
    }

    public function projectProtocolDescriptionEntries()
    {
        return $this->hasMany(ProjectProtocol::class, 'project_protocol_id');
    }
}
