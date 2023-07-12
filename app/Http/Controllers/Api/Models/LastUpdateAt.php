<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LastUpdateAt extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_updated_at',
    ];

    /**
     * set timestamps to false
     *
     * @var boolean
    */
    public $timestamps = false;

    protected $primaryKey = null;

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'last_update_ats';
}
