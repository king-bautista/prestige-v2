<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use App\Models\Site;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;
use App\Models\Brand;
use App\Models\Company;
use App\Models\Category;
use App\Models\CompanyCategory;
use App\Models\AdminViewModels\BrandProductViewModel;
use App\Models\AdminViewModels\SiteViewModel;

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

    public $brand_details = '';

    static $site_id = null;

    /**
     * Append additiona info to the return data
     *
     * @var string
     */
	public $appends = [
        'brand_name',
        'brand_logo',
        'category_id',
        'category_name',
        'parent_category_id',
        'parent_category_name',
        'main_category_id',
        'site_name',
        'building_name',
        'floor_name',
        'site_tenant_id',
        'tenant_details',
        'subscriber_logo',
        'operational_hours',
        'products',
        'location',
    ];

    static function setSiteId($id) {
        self::$site_id = $id;
    }

    public function getBrandDetails()
    {   
        $brand = Brand::find($this->brand_id);
        if(!$brand)
            return null;

        $category = Category::find($brand->category_id);
        $parent_category = '';
        if($category)
            $parent_category = Category::find($category->parent_id);

        return [
            'brand' => ($brand) ? $brand : null,
            'category' => ($category) ? $category : null,
            'parent_category' => ($parent_category) ? $parent_category : null,
        ];
    }

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
    public function getBrandNameAttribute()
    {
        $this->brand_details = $this->getBrandDetails();
        if(isset($this->brand_details['brand']))
            return $this->brand_details['brand']->name; 
        return null;
    }

    public function getBrandLogoAttribute() 
    {
        if(isset($this->brand_details['brand']->logo))
            return asset($this->brand_details['brand']->logo);  

        if(isset($this->brand_details['brand']->category_id)) {
            $illustration = CompanyCategory::where('sub_category_id', $this->brand_details['brand']->category_id)->where('site_id', $this->site_id)->first();
            if($illustration)
                return asset($illustration->kiosk_image_primary);
            return asset('/images/no-image-available.png');    
        }

        return asset('/images/no-image-available.png');
    }

    public function getCategoryIdAttribute()
    {
        if(isset($this->brand_details['category']->id))
            return $this->brand_details['category']->id;
        return null;
    }

    public function getCategoryNameAttribute()
    {
        if(isset($this->brand_details['category']->name))
            return $this->brand_details['category']->name;
        return null; 
    }

    public function getParentCategoryIdAttribute()
    {
        if(isset($this->brand_details['parent_category']->id))
            return $this->brand_details['parent_category']->id;
        return null;
    }

    public function getParentCategoryNameAttribute()
    {
        if(isset($this->brand_details['parent_category']->name))
            return $this->brand_details['parent_category']->name;
        return null;
    }

    public function getMainCategoryIdAttribute() 
    {
        return $this->parent_category_id;
    }

    public function getSiteNameAttribute() 
    {
        return Site::find($this->site_id)->name;
    }

    public function getBuildingNameAttribute() 
    {
        return SiteBuilding::find($this->site_building_id)->name;
    }

    public function getFloorNameAttribute() 
    {
        return SiteBuildingLevel::find($this->site_building_level_id)->name;
    }

    public function getSiteTenantIdAttribute() 
    {
        return $this->id;
    }

    public function getTenantDetailsAttribute() 
    {
        $tenant_details = $this->getTenantDetails()->pluck('meta_value','meta_key');
        if(count($tenant_details) > 0) {
            $tenant_details['schedules'] = json_decode($tenant_details['schedules']);
            return $tenant_details;
        }
        return null;
    }

    public function getSubscriberLogoAttribute() 
    {
        if(isset($this->tenant_details->subscriber_logo))
            return asset($this->tenant_details->subscriber_logo);
        return null;
    }

    function getTodaySchedule($json_data) {
        $current_day = Carbon::now()->isoFormat('ddd');

        if(is_array($json_data)) {
            foreach($json_data as $data) {
                if(strpos("Schedule, ".$data->schedules, $current_day)) {
                    $new_schedule = [
                        'is_open' => (strtotime($data->start_time) <= time() && strtotime($data->end_time) >= time()) ? 1 : 0,
                        'start_time' => date("h:ia",strtotime($data->start_time)),
                        'end_time' => date("h:ia",strtotime($data->end_time)),
                    ];
                    return $new_schedule;
                }
            }

            return [
                'is_open' => 0,
                'start_time' => '',
                'end_time' => '',
            ];
        }

        if(strpos($json_data->schedules, $current_day)) {
            $new_schedule = [
                'is_open' => (strtotime($json_data->start_time) <= time() && strtotime($json_data->end_time) >= time()) ? 1 : 0,
                'start_time' => date("h:ia",strtotime($json_data->start_time)),
                'end_time' => date("h:ia",strtotime($json_data->end_time)),
            ];
            return $new_schedule;
        }

        return [
            'is_open' => 0,
            'start_time' => '',
            'end_time' => '',
        ];     
    }

    public function getOperationalHoursAttribute() 
    {
        $new_schedule = [];
        $schedules = $this->getTenantDetails()->where('meta_key', 'schedules')->first();
        
        if($schedules) {
            $json_data = json_decode($schedules->meta_value);
            
            if(count($json_data) > 1) {
                foreach($json_data as $data) {
                    $today_schedule = $this->getTodaySchedule($data);
                    if($today_schedule['is_open'] == 1)
                        return $today_schedule;
                }
            }
            else {
                return $this->getTodaySchedule($json_data);
            }            
        }

        $site_id = self::$site_id;
        $site = SiteViewModel::find($site_id);
        if(isset($site->operational_hours))
            return $site->operational_hours;

        return null;
    }

    public function getProductsAttribute() 
    {
        $new_products = [];
        $product_ids = $this->getTenantProducts()->get()->pluck('brand_product_promo_id');
        if(count($product_ids) > 0) {
            $products = BrandProductViewModel::whereIn('id', $product_ids)->get();
            foreach($products as $product) {
                if($product->type == 'banner') {
                    $new_products['banners'][] = $product;
                }
                else {
                    $new_products['product_list'][] = $product;
                }
            }
            return $new_products;
        }
        return null;
    }

    public function getLocationAttribute() 
    {
        if(isset($this->tenant_details->address))
            return $this->tenant_details->address;
        return $this->floor_name;
    }
    
}
