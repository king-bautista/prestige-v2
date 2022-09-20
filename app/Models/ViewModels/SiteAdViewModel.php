<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\ViewModels\SiteTenantViewModel;
use App\Models\Site;

class SiteAdViewModel extends Model
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
    protected $table = 'site_ads';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $appends = [
        'material_image_path',
        'air_dates',
        'duration',
        'sites',
        'tenants',
        'site_names',
        'tenant_names',
    ]; 

    public function getTenantAds()
    {   
        $tenant_ids = $this->hasMany('App\Models\TenantAd', 'site_ad_id', 'id')->get()->pluck('site_tenant_id');
        return SiteTenantViewModel::whereIn('id', $tenant_ids)->get();
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getMaterialImagePathAttribute()
    {
        if($this->file_path)
            return asset($this->file_path);
        return asset('/images/no-image-available.png');
    } 

    public function getAirDatesAttribute()
    {
        return 'From: '.$this->start_date. ' To: '.$this->end_date;
    }
    
    public function getDurationAttribute()
    {
        return $this->display_duration. ' Sec ';
    }

    public function getTenantsAttribute()
    {
        return $this->getTenantAds();
    }

    public function getSitesAttribute()
    {
        $site_ids = array_unique($this->getTenantAds()->pluck('site_id')->toArray());
        return Site::whereIn('id', $site_ids)->get();
    }

    public function getSiteNamesAttribute()
    {
        $site_names = $this->getSitesAttribute()->pluck('name')->toArray();
        return implode(', ', $site_names);
    }

    public function getTenantNamesAttribute()
    {
        $tenant_names = $this->getTenantsAttribute()->pluck('brand_site_name')->toArray();
        return implode(', ', $tenant_names);
    }

}
