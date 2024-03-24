<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferRequestComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'description', 'estimated_work_in_h', 'type', 'offer_request_id'
    ];
    /**
     * Relationship with offer request
     */
    public function offerRequest()
    {
        return $this->belongsTo(OfferRequest::class, 'offer_request_id');
    }

    public function file()
    {
        return $this->morphOne(UploadedFile::class, 'fileable');
    }
}
