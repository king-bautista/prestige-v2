<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Category;
use App\Models\AdvertisementMaterial;

class PlayListViewModel extends Model
{
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
    protected $table = 'play_lists';

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
        'advertisement_serial_number',
        'advertisement_name',
        'material_path',
        'file_type',
        'start_date',
        'end_date',
        'active',
        'display_duration',
        'company_name',
        'site_name',
        'screen_location',
        'duration',
        'tenant_details',
        'brand_name',
        'category_name',
        'parent_category_name',
        'main_category_name',
    ];

    public function getAdvertisementDetails() {
        return $this->hasOne('App\Models\Advertisement', 'id', 'advertisement_id');
    }

    public function getMaterialDetails() {
        return AdvertisementMaterial::where('advertisement_id', $this->advertisement_id)->where('dimension', $this->dimension)->first();
    }

    public function getContentDetails() {
        return $this->hasOne('App\Models\ContentManagement', 'id', 'content_id');
    }

    public function getCompanyDetails() {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    public function getSiteScreenDetails()
    {
        return $this->hasOne('App\Models\ViewModels\SiteScreenViewModel', 'id', 'site_screen_id');
    }

    // public function getContentDetails()
    // {
    //     return $this->hasOne('App\Models\ViewModels\ContentManagementViewModel', 'id', 'content_id');
    // }



    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getAdvertisementSerialNumberAttribute() 
    {
        $advertisement = $this->getAdvertisementDetails()->first();
        if($advertisement)
            return $advertisement->serial_number;
        return null;
    }

    public function getAdvertisementNameAttribute() 
    {
        $advertisement = $this->getAdvertisementDetails()->first();
        if($advertisement)
            return $advertisement->name;
        return null;
    }

    public function getMaterialPathAttribute() 
    {
        $advertisement_material = $this->getMaterialDetails();
        if($advertisement_material)
            return $advertisement_material->file_path;
        return null;
    }

    public function getFileTypeAttribute()
    {
        $advertisement_material = $this->getMaterialDetails();
        if($advertisement_material)
            return $advertisement_material->file_type;
        return null;
    }

    public function getStartDateAttribute() 
    {
        $content_details = $this->getContentDetails()->first();
        if($content_details)
            return $content_details->start_date;
        return null;
    }

    public function getEndDateAttribute() 
    {
        $content_details = $this->getContentDetails()->first();
        if($content_details)
            return $content_details->end_date;
        return null;
    }

    public function getActiveAttribute() 
    {
        $content_details = $this->getContentDetails()->first();
        if($content_details)
            return $content_details->active;
        return null;
    }

    public function getDisplayDurationAttribute() 
    {
        $advertisement = $this->getAdvertisementDetails()->first();
        if($advertisement)
            return $advertisement->display_duration;
        return null;
    }

    public function getCompanyNameAttribute() 
    {
        $company_details = $this->getCompanyDetails()->first();
        if($company_details)
            return $company_details->name;
        return null;
    }

    public function getSiteNameAttribute() 
    {
        $site_details = $this->getSiteScreenDetails()->first();
        if($site_details)
            return $site_details->site_name;
        return null;
    }

    public function getScreenLocationAttribute() 
    {
        $site_details = $this->getSiteScreenDetails()->first();
        if($site_details)
            return $site_details->screen_location;
        return null;
    }

    public function getDurationAttribute()
    {
        $content_details = $this->getContentDetails()->first();
        $start_date = Carbon::parse($content_details->start_date);
        $end_date = Carbon::parse($content_details->end_date);    

        $total_days = $start_date->diffInDays($end_date);
        if($total_days)
            return $total_days. ' Days';
        return 0;
    }

    public function getTenantDetailsAttribute()
    {
        $site_details = $this->getSiteScreenDetails()->first();
        if($site_details) {
            return SiteTenantViewModel::where('site_id', $site_details->id)->where('brand_id', $this->brand_id);
        }
        return null;
    }

    public function getBrandNameAttribute() 
    {
        $brand = Brand::find($this->brand_id);
        if($brand)
            return $brand->name;
        return null;
    }  

    public function getCategoryNameAttribute() 
    {
        $category = Category::find($this->category_id);
        if($category)
            return $category->name;
        return null;
    }

    public function getParentCategoryNameAttribute() 
    {
        $category = Category::where('parent_id', $this->parent_category_id)->first();
        if($category)
            return $category->name;
        return null;
    }

    public function getMainCategoryNameAttribute() 
    {
        $category = Category::where('parent_id', $this->main_category_id)->first();
        if($category)
            return $category->name;
        return null;
    }

}
