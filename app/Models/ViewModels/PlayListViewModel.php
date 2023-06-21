<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Brand;

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
        'material_serial_number',
        'material_name',
        'material_path',
        'start_date',
        'end_date',
        'active',
        'display_duration',
        'company_name',
        'site_name',
        'screen_location',
        'duration',
        'file_type',
        'tenant_details',
        'brand_name',
        'category_name',
        'parent_category_name',
    ];

    public function getContentDetails()
    {
        return $this->hasOne('App\Models\ViewModels\ContentManagementViewModel', 'id', 'content_id');
    }

    public function getSiteScreenDetails()
    {
        return $this->hasOne('App\Models\ViewModels\SiteScreenViewModel', 'id', 'site_screen_id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getMaterialSerialNumberAttribute() 
    {
        $content_details = $this->getContentDetails()->first();
        if($content_details)
            return $content_details->serial_number;
        return null;
    }

    public function getMaterialNameAttribute() 
    {
        $content_details = $this->getContentDetails()->first();
        if($content_details)
            return $content_details->ad_name;
        return null;
    }

    public function getMaterialPathAttribute() 
    {
        $content_details = $this->getContentDetails()->first();
        if($content_details)
            return $content_details->material_path;
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
        $content_details = $this->getContentDetails()->first();
        if($content_details)
            return $content_details->advertisement_details->display_duration;
        return null;
    }

    public function getCompanyNameAttribute() 
    {
        $content_details = $this->getContentDetails()->first();
        if($content_details)
            return $content_details->company_name;
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

    public function getFileTypeAttribute()
    {
        $content_details = $this->getContentDetails()->first();
        if($content_details)
            return $content_details->advertisement_details->file_type;
        return null;
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
        $content_details = $this->getContentDetails()->first();
        if($content_details)
            return $content_details->category_name;
        return null;
    }

    public function getParentCategoryNameAttribute() 
    {
        $content_details = $this->getContentDetails()->first();
        if($content_details)
            return $content_details->parent_category_name;
        return null;
    }


}
