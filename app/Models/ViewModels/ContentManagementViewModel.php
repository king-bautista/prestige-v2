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
        'material_thumbnails_path',
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

    public function getAdvertisement()
    {
        return $this->hasOne('App\Models\ViewModels\AdvertisementViewModel', 'id', 'advertisement_id');
    }

    public function getMaterialDetails()
    {
        return $this->hasMany('App\Models\ViewModels\AdvertisementMaterialViewModel', 'advertisement_id', 'advertisement_id');
    }

    public function getScreens()
    {   
        return $this->hasMany('App\Models\ContentScreen', 'content_id', 'id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getMaterialThumbnailsPathAttribute() 
    {
        $ad_details = $this->getMaterialDetails()->first();
        if($ad_details)
            return $ad_details->material_path;
        return null;
    }

    public function getMaterialPathAttribute() 
    {
        $ad_details = $this->getMaterialDetails()->first();
        if($ad_details)
            return $ad_details->material_path;
        return null;
    }

    public function getAdNameAttribute() 
    {
        $ad_details = $this->getAdvertisement()->first();
        if($ad_details)
            return $ad_details->name;
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
        $ad_details = $this->getAdvertisement()->first();
        if($ad_details)
            return $ad_details->company_name;
        return null;
    }

    public function getBrandNameAttribute() 
    {
        $ad_details = $this->getAdvertisement()->first();
        if($ad_details)
            return $ad_details->brand_name;
        return null;
    }

    public function getCategoryNameAttribute()
    {
        $ad_details = $this->getAdvertisement()->first();
        if($ad_details)
            return $ad_details->category_name;
        return null;
    }

    public function getParentCategoryNameAttribute()
    {
        $ad_details = $this->getAdvertisement()->first();
        if($ad_details)
            return $ad_details->parent_category_name;
        return null;
    }

    public function getAdvertisementDetailsAttribute() 
    {
        $ad_details = AdvertisementViewModel::find($this->advertisement_id);
        if($ad_details)
            return $ad_details;
        return null;
    }    

    public function getScreensAttribute() 
    {
        $ids = $this->getScreens()->where('site_screen_id', '>', 0)->pluck('site_screen_id');
        $site_screens = SiteScreenViewModel::whereIn('id', $ids)->get();

        $ad_screens_ids = $this->getScreens()->where('site_screen_id', '=', 0)->where('site_id', '>', 0)->pluck('site_id');
        $ad_screens = SiteScreenViewModel::whereIn('site_id', $ad_screens_ids)->groupBy('site_id')->get();

        if($ad_screens) {
            foreach($ad_screens as $ad_screen) {
                $site_screens[] = [
                    'id' => 0,
                    'site_id' => $ad_screen->site_id,
                    'site_screen_location' => $ad_screen->site_code_name.' - All ('.$ad_screen->product_application.')',
                    'product_application' => $ad_screen->product_application
                ];
            }            
        }

        $all = $this->getScreens()->where('product_application', 'All')->get();
        if(count($all)) {
            $site_screens[] = [
                'id' => 0,
                'site_id' => 0,
                'site_screen_location' => 'All (Sites screens)',
                'product_application' => 'All'
            ];
        }

        if($site_screens)
            return $site_screens;
        return null;
    }  

    public function getAirDatesAttribute() 
    {
        return $this->start_date.' - '.$this->end_date;
    }  
}
