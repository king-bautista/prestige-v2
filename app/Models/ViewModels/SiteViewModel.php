<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

class SiteViewModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'sites';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Append additiona info to the return data
     *
     * @var string
     */
	public $appends = [
        'descriptions_ellipsis',
        'details',
        'site_logo_path',
        'site_banner_path',
        'site_background_path',
    ];

    public function getSiteDetails()
    {   
        return $this->hasMany('App\Models\SiteMeta', 'site_id', 'id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getDetailsAttribute() 
    {
        return $this->getSiteDetails()->pluck('meta_value','meta_key')->toArray();
    }

    public function getSiteLogoPathAttribute()
    {
        if($this->site_logo)
            return asset($this->site_logo);
        return asset('/images/no-image-available.png');
    }  

    public function getSiteBannerPathAttribute()
    {
        if($this->site_banner)
            return asset($this->site_banner);
        return asset('/images/no-image-available.png');
    }  

    public function getSiteBackgroundPathAttribute()
    {
        if($this->site_background)
            return asset($this->site_background);
        return asset('/images/no-image-available.png');
    } 

    public function getDescriptionsEllipsisAttribute()
    {
        if($this->descriptions)
            return Str::limit($this->descriptions, 150);
        return null;
    }  
}
