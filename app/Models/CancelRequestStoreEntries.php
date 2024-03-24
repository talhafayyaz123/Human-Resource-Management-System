<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelRequestStoreEntries extends Model
{
    use HasFactory;
    protected $fillable=['cancelation_request_id','store_entry_id'];
    protected $table='cancel_request_store_entry';

    

}
