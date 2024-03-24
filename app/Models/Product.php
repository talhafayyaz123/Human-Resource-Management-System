<?php

namespace App\Models;

use Faker\Provider\ar_EG\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends BaseModel
{
    use HasFactory;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    /**
     * Get the product category for the given resource
     */
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class)->orderBy('name', 'desc');
    }

    /**
     * Get the product group for the given resource
     */
    public function productGroup()
    {
        return $this->belongsTo(ProductGroup::class);
    }

    /**
     * Payment Period
     */
    public function paymentPeriod()
    {
        return $this->belongsTo(PaymentPeriod::class, 'payment_period_id');
    }

    /**
     *  Product Software
     */
    public function productSoftware()
    {
        return $this->belongsTo(ProductSoftware::class, 'product_software_id');
    }

    /**
     *  Product Software
     */
    public function infrastructureSetting()
    {
        return $this->belongsTo(EloBusinessSolutions::class, 'infrastructure_setting_id');
    }

    public function rules()
    {
        return $this->hasMany(ProductRule::class);
    }
    /**
     *  Product Invoice
     */
    public function productInvoice()
    {
        return $this->belongsTo(ProductInvoice::class);
    }

    /**
     *  Product Software
     */
    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }


    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function getisProductNameEditAttribute($value)
    {
        if($value==1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the elo version for the given resource
     */
    public function productEloVersion()
    {
        return $this->belongsTo(EloVersion::class, 'elo_version_id', 'id');
    }
    /**
     * Many to many relationship with systems
     */
    public function systems()
    {
        return $this->belongsToMany(System::class);
    }

    /**
     * belongs to relation with product price
     */

    public function productPrice()
    {
        return $this->belongsTo(PriceList::class, 'product_price_id');
    }

    public function scopeFilter($query, array $filters)
    {

        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
            $query->orWhere('status', 'like', str_contains('active', $search) ? 1 : (str_contains('deactive', $search) ? 0 : ""));
            $query->orWhere('article_number', 'like', '%' . $search . '%');
            $query->orWhereHas('productGroup', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status == 'active' ? 1 : 0);
        })->when($filters['productGroupId'] ?? null, function ($query, $productGroupId) {
            $query->where('product_group_id', $productGroupId);
        })->when($filters['productSoftwareId'] ?? null, function ($query, $productSoftwareId) {
            $query->where('product_software_id', $productSoftwareId);
        })->when($filters['productPriceId'] ?? null, function ($query, $productPriceId) {
            $query->where('product_price_id', $productPriceId);
        })->when($filters['type'] ?? null, function ($query, $type) {
            $query->where('payment_details_type', $type);
        });
    }


    public function versions()
    {
        return $this->hasMany(ProductVersion::class, 'product_id');
    }

    //services childrens
    public function productServiceChildrens()
    {
        return $this->belongsToMany(Product::class, 'product_service_childrens', 'product_id', 'child_id');
    }

    //services software childrens
    public function productServiceSoftwareChildrens()
    {
        return $this->belongsToMany(Product::class, 'product_service_software_childrens', 'product_id', 'product_version_id');
    }

    public function workshopTemplates()
    {
        return $this->belongsToMany(WorkshopTemplate::class, 'product_id', 'workshop_template_id');
    }
}
