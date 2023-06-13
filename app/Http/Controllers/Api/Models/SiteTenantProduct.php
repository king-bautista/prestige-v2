<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteTenantProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand_product_promo_id', 
        'site_tenant_id'
    ];

    /**
     * set timestamps to false
     *
     * @var boolean
    */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'site_tenant_products';
}
