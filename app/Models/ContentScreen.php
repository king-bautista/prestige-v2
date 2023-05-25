<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentScreen extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content_id', 
        'pi_product_id',
        'site_screen_id',
        'site_id',
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
    protected $table = 'content_screens';
}
