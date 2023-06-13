<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRoles extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id', 
        'role_id'
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
    protected $table = 'admin_roles';

}
