<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Helpers\GenerateUuidHelper;

class System extends BaseModel
{
    use HasFactory, SoftDeletes;

    // protected $fillable = ['id'];
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->id = GenerateUuidHelper::generateUUID();
    //     });
    // }
    /**
     * One to one relationship with user
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'customer_id');
    }

    /**
     * Many to many relationship with products
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    /**
     * Many to many relationship with installed Products
     */
    public function installedProducts()
    {
        return $this->belongsToMany(Product::class, 'product_system_installed')->withPivot('quantity');
    }


    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function cloudServers()
    {
        return $this->hasMany(CloudServer::class);
    }

    public function tenant()
    {
        return $this->hasOne(SystemTenant::class, 'system_id');
    }

    public function SystemTenant()
    {
        return $this->hasOne(SystemTenant::class, 'system_id');
    }

    public function hostDockerField()
    {
        return $this->hasOne(HostDockerField::class, 'system_id');
    }

    //need next id for getting the system number before saving
    public function getNextId()
    {
        $statement = DB::select("show table status like 'systems'");
        return $statement[0]->Auto_increment;
    }

    public function databaseClouds()
    {
        return $this->belongsToMany(DatabaseCloud::class, 'database_cloud_system');
    }

    public function distributedFilesystems()
    {
        return $this->belongsToMany(DistributedFilesystem::class, 'distributed_filesystem_system');
    }

    public function operatingSystem()
    {
        return $this->belongsTo(OperatingSystem::class, 'operating_system_id', 'id');
    }
    public function software()
    {
        return $this->belongsTo(ProductSoftware::class, 'product_software_id', 'id');
    }
    public function version()
    {
        return $this->belongsTo(EloVersion::class, 'elo_version_id');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }
}
