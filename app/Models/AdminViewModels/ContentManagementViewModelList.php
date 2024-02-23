<?php

namespace App\Models\AdminViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\TransactionStatus;

class ContentManagementViewModelList extends Model
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
         'advertisement_details',
         'material_thumbnails_path',
        // // 'material_path',
        // 'dimension',
         //'ad_name',
        // 'company_name',
        // 'brand_name',
        // 'air_dates',
        // 'screens',
    ];

    public function getAdvertisementDetails()
    {
        $ad_details = AdvertisementViewModelList::find($this->advertisement_id);
        if($ad_details)
            return $ad_details;
        return null;
    }

    // public function getScreens()
    // {   
    //     return $this->hasMany('App\Models\ContentScreen', 'content_id', 'id');
    // }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getAdvertisementDetailsAttribute() 
    {
        return $this->getAdvertisementDetails();
    }

    public function getMaterialThumbnailsPathAttribute() 
    {
        return $this->advertisement_details->material_thumbnails_path;
    }

    // public function getMaterialPathAttribute() 
    // {
    //     return $this->advertisement_details->material_image_path;
    // }

    // public function getDimensionAttribute() 
    // {
    //     return $this->advertisement_details->materials[0]->dimension;
    // }

    // public function getAdNameAttribute() 
    // {
    //     return $this->advertisement_details->name;
    // }

    // public function getCompanyNameAttribute() 
    // {
    //     return $this->advertisement_details->company_name;
    // }

    // public function getBrandNameAttribute() 
    // {
    //     return $this->advertisement_details->brand_name;
    // }

    // public function getAirDatesAttribute() 
    // {
    //     return $this->start_date.' - '.$this->end_date;
    // }

    // public function getScreensAttribute() 
    // {
    //     $ids = $this->getScreens()->where('site_screen_id', '>', 0)->pluck('site_screen_id');
    //     $site_screens = SiteScreenViewModel::whereIn('id', $ids)->get();

    //     $ad_screens_ids = $this->getScreens()->where('site_screen_id', '=', 0)->where('site_id', '>', 0)->pluck('site_id');
    //     $ad_screens = SiteScreenViewModel::whereIn('site_id', $ad_screens_ids)->groupBy('site_id')->get();

    //     if($ad_screens) {
    //         foreach($ad_screens as $ad_screen) {
    //             $site_screens[] = [
    //                 'id' => 0,
    //                 'site_id' => $ad_screen->site_id,
    //                 'site_screen_location' => $ad_screen->site_code_name.' - All ('.$ad_screen->product_application.')',
    //                 'product_application' => $ad_screen->product_application
    //             ];
    //         }            
    //     }

    //     $all = $this->getScreens()->where('product_application', 'All')->get();
    //     if(count($all)) {
    //         $site_screens[] = [
    //             'id' => 0,
    //             'site_id' => 0,
    //             'site_screen_location' => 'All (Sites screens)',
    //             'product_application' => 'All'
    //         ];
    //     }

    //     if($site_screens)
    //         return $site_screens;
    //     return null;
    // }  

}
