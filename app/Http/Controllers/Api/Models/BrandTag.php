<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandTag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand_id', 
        'tag_id'
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
    protected $table = 'brand_tags';
}
