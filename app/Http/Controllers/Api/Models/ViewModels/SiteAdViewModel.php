<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\ViewModels\SiteTenantViewModel;
use App\Models\Site;
use App\Models\Company;

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
        'screens',
        'site_names',
        'tenant_ids',
        'tenant_brand_ids',
        'tenant_names',
        'screen_names',
        'company_name',
        'company_details',
    ]; 

    public function getAdSites()
    {   
        $site_ids = $this->hasMany('App\Models\SiteAdSite', 'site_ad_id', 'id')->get()->pluck('site_id');
        return SiteViewModel::whereIn('id', $site_ids)->get();
    }

    public function getAdTenants()
    {   
        $tenant_ids = $this->hasMany('App\Models\SiteAdTenant', 'site_ad_id', 'id')->get()->pluck('site_tenant_id');
        return SiteTenantViewModel::whereIn('id', $tenant_ids)->get();
    }

    public function getAdScreens()
    {   
        $screen_ids = $this->hasMany('App\Models\SiteAdScreen', 'site_ad_id', 'id')->get()->pluck('site_screen_id');
        return SiteScreenViewModel::whereIn('id', $screen_ids)->get();
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
        return $this->getAdTenants();
    }

    public function getScreensAttribute()
    {
        return $this->getAdScreens();
    }

    public function getSitesAttribute()
    {
        return $this->getAdSites();
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

    public function getTenantIdsAttribute()
    {
        $tenant_ids = $this->getTenantsAttribute()->pluck('id')->toArray();
        return implode(', ', $tenant_ids);
    }

    public function getTenantBrandIdsAttribute()
    {
        $tenant_ids = $this->getTenantsAttribute()->pluck('brand_id')->toArray();
        return implode(', ', $tenant_ids);
    }

    public function getScreenNamesAttribute()
    {
        $screen_names = $this->getScreensAttribute()->pluck('screen_type_name')->toArray();
        return implode(', ', $screen_names);
    }

    public function getCompanyNameAttribute()
    {
        $company = Company::find($this->company_id);
        if($company)
            return $company['name'];
        return null;
    }

    public function getCompanyDetailsAttribute()
    {
        $company = Company::find($this->company_id);
        if($company)
            return $company;
        return null;
    }

}
