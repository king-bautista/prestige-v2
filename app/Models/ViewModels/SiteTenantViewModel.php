<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Site;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;
use App\Models\Brand;
use App\Models\Category;

use Carbon\Carbon;

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
        'category_id',
        'parent_category_id',
        'category_name',
        'tenant_details',
        'subscriber_logo',
        'operational_hours',
        'products',
        'property_owner',
        'store_address',
        'company_name',
    ];

    public function getTenantDetails()
    {   
        return $this->hasMany('App\Models\SiteTenantMeta', 'site_tenant_id', 'id');
    }

    public function getTenantProducts()
    {   
        return $this->hasMany('App\Models\SiteTenantProduct', 'site_tenant_id', 'id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getBranddetailsAttribute() 
    {
        return BrandViewModel::find($this->brand_id);
    }

    public function getBrandLogoAttribute() 
    {
        $brand = Brand::find($this->brand_id);
        if($brand){
            if(strlen($brand->logo) > 0) 
                return asset($brand->logo);
            return asset('/images/no-image-available.png');            
        }
        return asset('/images/no-image-available.png');
    }

    public function getBrandNameAttribute() 
    {
        $band = Brand::find($this->brand_id);
        if($band)
            return $band->name;
        return null;
    }

    public function getBuildingNameAttribute() 
    {
        $site_building = SiteBuilding::find($this->site_building_id);
        if($site_building)
            return $site_building->name;
        return null;
    }

    public function getFloorNameAttribute() 
    {
        $site_building_level = SiteBuildingLevel::find($this->site_building_level_id);
        if($site_building_level)
            return $site_building_level->name;
        return null;
    }

    public function getSiteNameAttribute() 
    {
        $site = Site::find($this->site_id);
        if($site)
            return $site->name;
        return null;
    }

    public function getBrandSiteNameAttribute() 
    {
        $brand = Brand::find($this->brand_id);
        $site = Site::find($this->site_id);

        if($brand && $site)
            return $site->name.' - '.$brand->name;
        return null;
    }

    public function getCategoryIdAttribute() 
    {
        $brand_category = Brand::find($this->brand_id);
        if($brand_category)
            return $brand_category->category_id;
        return null;
    }

    public function getParentCategoryIdAttribute() 
    {
        $brand = Brand::find($this->brand_id);
        if($brand) {
            $category = Category::find($brand->category_id);
            if($category)
                return $category->parent_id;
            return null;
        }
        return null;
    }    
    
    public function getCategoryNameAttribute() 
    {
        $brand = Brand::find($this->brand_id);
        if($brand) {
            $category = Category::find($brand->category_id);
            if($category)
                return $category->name;
            return null;
        }
        return null;
    }

    public function getTenantDetailsAttribute() 
    {
        return $this->getTenantDetails()->pluck('meta_value','meta_key')->toArray();
    }

    public function getSubscriberLogoAttribute() 
    {
        $subscriber_logo = $this->getTenantDetails()->where('meta_key', 'subscriber_logo')->first();
        if(isset($subscriber_logo->meta_value))
            return asset($subscriber_logo->meta_value);
        return null;
    }

    public function getOperationalHoursAttribute() 
    {
        $new_schedule = [];
        $current_day = Carbon::now()->isoFormat('ddd');
        $schedules = $this->getTenantDetails()->where('meta_key', 'schedules')->first();
        if($schedules) {
            $json_data = json_decode($schedules->meta_value);
            
            foreach($json_data as $data) {
                if(strpos($data->schedules, $current_day)) {
                    $new_schedule = [
                        'is_open' => (strtotime($data->start_time) <= time() && strtotime($data->end_time) >= time()) ? 1 : 0,
                        'start_time' => date("h:ia",strtotime($data->start_time)),
                        'end_time' => date("h:ia",strtotime($data->end_time)),
                    ];
                    return $new_schedule;
                }
            }
        }
        return null;
    }

    public function getProductsAttribute() 
    {
        $new_products = [];
        $product_ids = $this->getTenantProducts()->get()->pluck('brand_product_promo_id');
        if($product_ids) {
            $products = BrandProductViewModel::whereIn('id', $product_ids)->where('type', '!=', 'promo')->get();
            foreach($products as $product) {
                if($product->type == 'banner') {
                    $new_products['banners'][] = $product;
                }
                else {
                    $new_products['products'][] = $product;
                }
            }
            return $new_products;
        }
        return null;
    }

    public function getPropertyOwnerAttribute()
    {
        $site_details = SiteViewModel::find($this->site_id);
        if($site_details)
            return $site_details->property_owner;
        return null;
    }

    public function getStoreAddressAttribute() 
    {
        $tenant_details = $this->getTenantDetails()->where('meta_key', 'address')->first();
        if(isset($tenant_details->meta_value))
            return $tenant_details->meta_value;
        return null;
    }

    public function getCompanyNameAttribute() 
    {
        $company = Brand::find($this->company_id);
        if($company)
            return $company->name;
        return null;
    }
    
}
