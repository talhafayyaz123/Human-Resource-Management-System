<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ContinuousImprovementProcessApprover extends Pivot
{
    use HasFactory;

    protected $table = "continuous_improvement_process_approvers";

    public function continuousImprovementProcess()
    {
        return $this->belongsTo(ContinuousImprovementProcess::class, 'cip_id');
    }

    public function approver()
    {
        return $this->belongsTo(UserProfileData::class, 'approver_id');
    }
}
