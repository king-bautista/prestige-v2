<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Site;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;
use App\Models\Brand;

class SiteTenantViewModel extends Model
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
    protected $table = 'site_tenants';

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
        'brand_details',
        'brand_logo',
        'brand_name',
        'building_name',
        'floor_name',
        'site_name',
        'brand_site_name',
    ];

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getBranddetailsAttribute() 
    {
        return Brand::find($this->brand_id);
    }

    public function getBrandLogoAttribute() 
    {
        $logo = Brand::find($this->brand_id)->logo;
        if($logo)
            return asset($logo);
        return asset('/images/no-image-available.png');
    }

    public function getBrandNameAttribute() 
    {
        return Brand::find($this->brand_id)->name;
    }

    public function getBuildingNameAttribute() 
    {
        return SiteBuilding::find($this->site_building_id)->name;
    }

    public function getFloorNameAttribute() 
    {
        return SiteBuildingLevel::find($this->site_building_level_id)->name;
    }

    public function getSiteNameAttribute() 
    {
        return Site::find($this->site_id)->name;
    }

    public function getBrandSiteNameAttribute() 
    {
        $brand_name = Brand::find($this->brand_id)->name;
        $site_name = Site::find($this->site_id)->name;
        return $brand_name.' - '.$site_name;
    }

}
