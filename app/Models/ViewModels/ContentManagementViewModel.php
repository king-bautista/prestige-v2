<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\TransactionStatus;

class ContentManagementViewModel extends Model
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
    protected $table = 'content_management';

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
        'material_path',
        'ad_name',
        'dimension',
        'company_name',
        'brand_name',
        'category_name',
        'parent_category_name',
        'air_dates',
        'advertisement_details',
        'screens',
    ];

    public function getMaterialDetails()
    {
        return $this->hasOne('App\Models\ViewModels\ContentMaterialViewModel', 'id', 'material_id');
    }

    public function getScreens()
    {   
        return $this->hasMany('App\Models\ContentScreen', 'content_id', 'id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getMaterialPathAttribute() 
    {
        $ad_details = $this->getMaterialDetails()->first();
        if($ad_details)
            return $ad_details->material_path;
        return null;
    }

    public function getAdNameAttribute() 
    {
        $ad_details = $this->getMaterialDetails()->first();
        if($ad_details)
            return $ad_details->advertisement_name;
        return null;
    }
    
    public function getDimensionAttribute() 
    {
        $ad_details = $this->getMaterialDetails()->first();
        if($ad_details)
            return $ad_details->dimension;
        return null;
    }

    public function getCompanyNameAttribute() 
    {
        $ad_details = $this->getMaterialDetails()->first();
        if($ad_details)
            return $ad_details->company_name;
        return null;
    }

    public function getBrandNameAttribute() 
    {
        $ad_details = $this->getMaterialDetails()->first();
        if($ad_details)
            return $ad_details->brand_name;
        return null;
    }

    public function getCategoryNameAttribute()
    {
        $ad_details = $this->getMaterialDetails()->first();
        if($ad_details)
            return $ad_details->category_name;
        return null;
    }

    public function getParentCategoryNameAttribute()
    {
        $ad_details = $this->getMaterialDetails()->first();
        if($ad_details)
            return $ad_details->parent_category_name;
        return null;
    }

    public function getAdvertisementDetailsAttribute() 
    {
        $ad_details = $this->getMaterialDetails()->first();
        if($ad_details)
            return $ad_details;
        return null;
    }    

    public function getScreensAttribute() 
    {
        $ids = $this->getScreens()->pluck('site_screen_product_id');
        $site_screen_products = SiteScreenProductViewModel::whereIn('id', $ids)->get();

        if($site_screen_products)
            return $site_screen_products;
        return null;
    }  

    public function getAirDatesAttribute() 
    {
        return $this->start_date.' - '.$this->end_date;
    }  
}
