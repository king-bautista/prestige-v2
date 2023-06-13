<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyBrands extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 
        'brand_id'
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
    protected $table = 'company_brands';
}
