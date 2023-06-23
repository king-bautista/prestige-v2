<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;
use App\Models\Company;

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
        'short_code',
        'site_logo_path',
        'site_banner_path',
        'site_background_path',
        'is_premiere',
        'multilanguage',
        'company_id',
        'property_owner',
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
            return Str::limit($this->descriptions, 95);
        return null;
    }
    
    public function getShortCodeAttribute()
    {
        $site_details = $this->getSiteDetails()->where('meta_key', 'site_code')->first();
        if($site_details)
            return $site_details->meta_value;
        return null;
    }

    public function getIsPremiereAttribute()
    {
        $site_details = $this->getSiteDetails()->where('meta_key', 'is_premiere')->first();
        if($site_details)
            return $site_details->meta_value;
        return 0;
    }

    public function getMultilanguageAttribute()
    {
        $site_details = $this->getSiteDetails()->where('meta_key', 'multilanguage')->first();
        if($site_details)
            return $site_details->meta_value;
        return 0;
    }

    public function getCompanyIdAttribute()
    {
        $site_details = $this->getSiteDetails()->where('meta_key', 'company_id')->first();
        if($site_details)
            return $site_details->meta_value;
        return null;
    }

    public function getPropertyOwnerAttribute()
    {
        $company_details = Company::find($this->company_id);
        if($company_details)
            return $company_details->name;
        return null;
    }
}
