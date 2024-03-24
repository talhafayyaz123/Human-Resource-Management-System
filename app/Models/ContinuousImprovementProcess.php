<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ContinuousImprovementProcess extends Model
{
    use HasFactory;

    protected $table = "continuous_improvement_process";

    protected $fillable = ["process_number", "title", "date", "description", "suggested_solution", "affected_group_id", "request_number", "user_id"];

    public function affectedGroup()
    {
        return $this->belongsTo(AffectedGroup::class, "affected_group_id");
    }

    /**
     * Relationship of uploaded file
     */
    public function files()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }

    public function approvers()
    {
        return $this->belongsToMany(UserProfileData::class, 'continuous_improvement_process_approvers', 'cip_id', 'approver_id')->withPivot(['status', 'approver_type'])->using(ContinuousImprovementProcessApprover::class);
    }
}
