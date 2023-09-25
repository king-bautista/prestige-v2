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

class DirectorySiteTenantViewModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'category_type',
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

    public $appends = [
        'brand_name',
        'brand_logo',
        'company_name',
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
    ]; 

    public function getCompany()
    {   
        $company = Company::find($this->company_id);
        if($company)
            return $company;
        return null;
    }

    public function getBrand()
    {   
        $brand = Brand::find($this->brand_id);
        if($brand)
            return $brand;
        return null;
    }

    public function getCategory()
    {   
        $brand = $this->getBrand();
        if(!$brand)
            return null;

        $category = Category::find($brand->category_id);
        if($category)
            return $category;
        return null;
    }

    public function getParentCategory()
    {   
        $category = $this->getCategory();
        if(!$category)
            return null;

        $parent_category = Category::find($category->parent_id);
        if($parent_category)
            return $parent_category;
        return null;
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
        if($this->getBrand())
            return $this->getBrand()->name; 
        return null;
    }

    public function getBrandLogoAttribute() 
    {
        $brand = $this->getBrand();
        if($brand) {
            if(strlen($brand->logo) > 0) 
                return asset($brand->logo);            
        }


        if(isset($brand->category_id)) {
            $illustration = CompanyCategory::where('sub_category_id', $this->category_id)->where('site_id', $this->site_id)->first();
            if($illustration)
                return asset($illustration->kiosk_image_primary);
            return asset('/images/no-image-available.png');    
        }

        return asset('/images/no-image-available.png');
    }

    public function getCompanyNameAttribute()
    {
        if($this->getCompany())
            return $this->getCompany()->name; 
        return null;
    }

    public function getCategoryIdAttribute()
    {
        if($this->getCategory())
            return $this->getCategory()->id;
        return null; 
    }

    public function getCategoryNameAttribute()
    {
        if($this->getCategory())
            return $this->getCategory()->name;
        return null; 
    }

    public function getParentCategoryIdAttribute()
    {
        if($this->getParentCategory())
            return $this->getParentCategory()->id;
        return null; 
    }

    public function getParentCategoryNameAttribute()
    {
        if($this->getParentCategory())
            return $this->getParentCategory()->name;
        return null;
    }

    public function getMainCategoryIdAttribute() 
    {
        $parent_category = $this->getParentCategory();
        $parent_category_id = null;

        if($parent_category && !isset($parent_category['supplemental_category_id']))
            return $parent_category['id'];

        if(isset($parent_category['supplemental_category_id']))
            return Category::find($parent_category['supplemental_category_id'])->id;

        return null;
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
        return $this->getTenantDetails()->pluck('meta_value','meta_key')->toArray();
    }

    public function getSubscriberLogoAttribute() 
    {
        $subscriber_logo = $this->getTenantDetails()->where('meta_key', 'subscriber_logo')->first();
        if($subscriber_logo)
            return asset($subscriber_logo->meta_value);
        return asset('/images/no-image-available.png');
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
                    $new_products['product_list'][] = $product;
                }
            }
            return $new_products;
        }
        return null;
    }




}
