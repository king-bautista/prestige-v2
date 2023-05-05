<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractScreen extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contract_id',
        'site_screen_id',
        'site_id',
        'product_application',
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
    protected $table = 'contract_screens';
}
