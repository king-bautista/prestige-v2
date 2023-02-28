<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Site;
use App\Models\ContentScreen;
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
        'advertisement_details',
        'site_details',
        'screens',
        'tenant_details',
        'status_details',
        'ad_name',
        'material_image_path',
        'company_id',
        'company_name',
        'brand_id',
        'brand_name',
        'category_id',
        'category_name',
        'parent_category_id',
        'parent_category_name',
        'main_category_id',
        'transaction_status',
        'display_duration',
        'dimension',
        'site_name',
        'ad_type',
        'file_type',
    ];

    public function getAdvertisementDetails()
    {   
        return AdvertisementViewModel::find($this->advertisement_id);
    }

    public function getSiteDetails()
    {   
        return Site::find($this->site_id);
    }

    public function getScreens()
    {
        $screen_ids = ContentScreen::where('content_id', $this->id)->get()->pluck('site_screen_id');
        return SiteScreenViewModel::whereIn('id', $screen_ids)->get();
    }

    public function getTenantDetails()
    {
        return SiteTenantViewModel::find($this->site_tenant_id);
    }

    public function getTransactionStatus()
    {
        return TransactionStatus::find($this->status_id);
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getAdvertisementDetailsAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details;
        return null;
    }
    
    public function getAdNameAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->name;
        return null;
    }

    public function getMaterialImagePathAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->material_image_path;
        return null;
    }

    public function getCompanyIdAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->company_id;
        return null;
    }

    public function getCompanyNameAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->company_name;
        return null;
    }

    public function getBrandIdAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->brand_id;
        return null;
    }

    public function getBrandNameAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->brand_name;
        return null;
    }

    public function getCategoryIdAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->category_id;
        return null;
    }

    public function getCategoryNameAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->category_name;
        return null;
    }

    public function getParentCategoryIdAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->parent_category_id;
        return null;
    }

    public function getParentCategoryNameAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->parent_category_name;
        return null;
    }

    public function getMainCategoryIdAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->parent_category_id;
        return null;
    }

    public function getTransactionStatusAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->transaction_status;
        return null;
    }

    public function getDisplayDurationAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->display_duration;
        return null;
    }

    public function getDimensionAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->dimension;
        return null;
    }

    public function getSiteNameAttribute() 
    {
        $site = Site::find($this->site_id);
        if($site)
            return $site->name;
        return null;
    }

    public function getSiteDetailsAttribute() 
    {
        $site = $this->getSiteDetails();
        if($site)
            return $site;
        return null;
    }

    public function getScreensAttribute() 
    {
        $screens = $this->getScreens();
        if($screens)
            return $screens;
        return null;
    }

    public function getTenantDetailsAttribute() 
    {
        $screens = $this->getTenantDetails();
        if($screens)
            return $screens;
        return null;
    }

    public function getStatusDetailsAttribute()
    {
        $status = $this->getTransactionStatus();
        if($status)
            return $status;
        return null;
    }
    
    public function getAdTypeAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->ad_type;
        return null;
    }

    public function getFileTypeAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->file_type;
        return null;
    }

    

}
