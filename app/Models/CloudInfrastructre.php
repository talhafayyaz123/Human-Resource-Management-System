<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\GenerateUuidHelper;

class CloudInfrastructre extends BaseModel
{
    use HasFactory;
    protected $table = "cloud_infrastructures";

    protected $fillable = ['id'];
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [
        'created_at', 'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = GenerateUuidHelper::generateUUID();
        });
    }

    public function cloudServers()
    {
        return $this->hasMany(CloudInfrastructureServer::class, 'cluster_id');
    }
}
