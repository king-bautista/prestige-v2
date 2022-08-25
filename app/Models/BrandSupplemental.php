<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandSupplemental extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand_id', 
        'supplemental_id'
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
    protected $table = 'brand_supplementals';
}
