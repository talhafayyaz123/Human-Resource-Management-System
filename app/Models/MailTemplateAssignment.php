<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailTemplateAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['module', 'mail_template_id', 'cc', 'bcc', 'sender_mail'];
}
