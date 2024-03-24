<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformedTicketCommentUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ticket_comment_id'];

    // relation with ticket comment
    public function ticketComment()
    {
        return $this->belongsTo(TicketComment::class);
    }
}
