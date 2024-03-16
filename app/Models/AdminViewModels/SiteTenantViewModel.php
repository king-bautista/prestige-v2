<?php

namespace App\Models\AdminViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use App\Models\Site;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;
use App\Models\BrandTag;
use App\Models\Tag;

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
        'tenant_details',
        'brand_details',
        'site_details',
        'brand_logo',
        'brand_name',
        'site_name',
        'building_name',
        'floor_name',
        'store_address',
        'property_owner',
        'tags',
    ];

    public function getTenantDetails()
    {   
        return $this->hasMany('App\Models\SiteTenantMeta', 'site_tenant_id', 'id');
    }

    public function getBrandDetails()
    {   
        $brand = BrandViewModel::find($this->brand_id);
        if($brand)
            return $brand;
        return null;
    }

    public function getSiteDetails()
    {   
        $site = Site::find($this->site_id);
        $site_building = SiteBuilding::find($this->site_building_id);
        $site_building_level = SiteBuildingLevel::find($this->site_building_level_id);

        return [
            'site_name' => ($site) ? $site->name : null,
            'site_building' => ($site_building) ? $site_building->name : null,
            'site_building_level' => ($site_building_level) ? $site_building_level->name : null
        ]; 
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getTenantDetailsAttribute() 
    {
        return $this->getTenantDetails()->pluck('meta_value','meta_key')->toArray();
    }

    public function getBrandDetailsAttribute() 
    {
        return $this->getBrandDetails();
    }

    public function getSiteDetailsAttribute() 
    {
        return $this->getSiteDetails();
    }

    public function getBrandLogoAttribute() 
    {
        $brand = BrandViewModel::find($this->brand_id);
        if($brand)
            return $brand->logo_image_path;
        return '';
        // $this->brand_details->logo_image_path;
    }

    public function getBrandNameAttribute() 
    {     
        $brand = BrandViewModel::find($this->brand_id);
        if($brand)
            return $brand->name;
        return '';
       // return $this->brand_details->name;
    }

    public function getSiteNameAttribute() 
    {
        return $this->site_details['site_name'];
    }

    public function getBuildingNameAttribute() 
    {
        return $this->site_details['site_building'];
    }

    public function getFloorNameAttribute() 
    {
        return $this->site_details['site_building_level'];
    }

    public function getStoreAddressAttribute() 
    {
        if(count($this->tenant_details) > 0) {
            return $this->tenant_details['address'];
        }
        return $this->site_details['site_building']. ', '.$this->site_details['site_building_level'];
    }

    public function getPropertyOwnerAttribute()
    {
        $site_details = SiteViewModel::find($this->site_id);
        if($site_details)
            return $site_details->property_owner;
        return null;
    }

    public function getTagsAttribute()
    {
        $tag_ids = '';
        $brand_tags = BrandTag::where('tenant_id', $this->id)->get();
        if(count($brand_tags) > 0) {
            $tag_ids = $brand_tags->pluck('tag_id');
            $tags = Tag::whereIn('id', $tag_ids)->get();
            if($tags)
                return $tags;
            return null;
        }

        if($this->brand_details) {
            return $this->brand_details['brand_details']['tags'];
        }

        return null;        
    }

}
