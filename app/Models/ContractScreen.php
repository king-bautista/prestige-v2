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

    /**
     * Append additiona info to the return data
     *
     * @var string
     */
	public $appends = [
        'site_name',
        'site_code_name',
    ];

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getSiteNameAttribute() 
    {
        $site = Site::find($this->site_id);
        if($site)
            return $site->name;
        return null;
    }

    public function getSiteCodeNameAttribute() 
    {
        $site_meta = SiteMeta::where('site_id', $this->site_id)->where('meta_key', 'site_code')->first();
        if($site_meta)
            return $site_meta->meta_value;
        return null;
    }
}
