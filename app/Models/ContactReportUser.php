<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactReportUser extends BaseModel
{
    use HasFactory;

    /**
     * Relation to which report
     */
    public function report()
    {
        return $this->belongsTo(ContactReport::class);
    }
}
