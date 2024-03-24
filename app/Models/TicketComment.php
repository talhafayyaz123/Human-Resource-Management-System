<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketComment extends BaseModel
{
    use HasFactory;

    /**
     * Get the parent user model.
     */
    public function user()
    {
        return $this->morphTo();
    }

    /**
     * Get the ticket that this comment belongs to.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function company()
    {
        return $this->morphOne(TimeTrackerCompany::class, 'module');
    }

    /**
     * Relationship of uploaded file
     */
    public function files()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }

    // relation with informed_ticket_comment_users
    public function informedUsers()
    {
        return $this->hasMany(InformedTicketCommentUser::class);
    }
}
