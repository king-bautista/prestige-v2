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
        return $site_name.' - '.$brand_name;
    }

    public function getCategoryIdAttribute() 
    {
        $brand_category_id = Brand::find($this->brand_id)->category_id;
        if($brand_category_id)
            return $brand_category_id;
        return null;
    }

    public function getParentCategoryIdAttribute() 
    {
        $brand_category_id = Brand::find($this->brand_id)->category_id;
        $category = Category::find($brand_category_id);
        if($category)
            return $category['parent_id'];
        return null;
    }    
    
    public function getCategoryNameAttribute() 
    {
        $brand_category_id = Brand::find($this->brand_id)->category_id;
        $category_name = Category::find($brand_category_id);
        if($category_name)
            return $category_name['name'];
        return null;
    }

    public function getTenantDetailsAttribute() 
    {
        return $this->getTenantDetails()->pluck('meta_value','meta_key')->toArray();
    }

    public function getSubscriberLogoAttribute() 
    {
        $subscriber_logo = $this->getTenantDetails()->where('meta_key', 'subscriber_logo')->first();
        if($subscriber_logo)
            return asset($subscriber_logo->meta_value);
        return asset('/images/no-image-available.png');
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
    
}
