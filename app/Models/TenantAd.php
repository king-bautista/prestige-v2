<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantAd extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_ad_id', 
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
    protected $table = 'tenant_ads';
}
