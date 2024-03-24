<?php

namespace App\Models;

use App\Helpers\GenerateUuidHelper;
use App\Utils\Logger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id'];
    public $incrementing = false;
    protected $keyType = 'string';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = GenerateUuidHelper::generateUUID();
        });
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('supplier_name', 'like', '%' . $search . '%');
        });
    }

    /**
     * Get the locations that this supplier has.
     */
    public function locations()
    {
        return $this->hasMany(SupplierLocation::class);
    }

    /**
     * Get the bank details that this supplier has.
     */
    public function bankDetails()
    {
        return $this->hasMany(SupplierBankDetail::class, 'supplier_id');
    }

    //getting the head quater of a specific location
    public function headQuarters()
    {
        return $this->hasOne(SupplierLocation::class)->where('is_head_quarters', true);
    }



    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }

    public function image()
    {
        return $this->morphOne(UploadedFile::class, 'fileable');
    }
}
