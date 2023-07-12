<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Site;

class AdvertisementScreen extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'advertisement_id', 
        'material_id',
        'pi_product_id',
        'site_screen_id',
        'site_id',
        'ad_type',
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
    protected $table = 'advertisement_screens';

    /**
     * Append additiona info to the return data
     *
     * @var string
     */
	public $appends = [
        'site_name',
    ];

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getSiteNameAttribute() 
    {
        return Site::find($this->site_id)->name;
    }
}
