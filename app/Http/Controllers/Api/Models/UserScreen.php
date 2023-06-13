<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserScreen extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'site_screen_id'
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
    protected $table = 'user_screens';
}
