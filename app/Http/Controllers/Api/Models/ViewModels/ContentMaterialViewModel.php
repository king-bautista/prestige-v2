<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentMaterialViewModel extends Model
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
    protected $table = 'advertisement_materials';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $appends = [
        'material_path',
        'site_screen_products',
        'advertisement_name',
        'company_name',
        'brand_name',
        'screen_assigned',
        'display_duration',
    ]; 

    public function getScreens()
    {   
        return $this->hasMany('App\Models\AdvertisementScreen', 'material_id', 'id');
    }

    public function getAdvertisement()
    {
        return $this->hasOne('App\Models\ViewModels\AdvertisementViewModel', 'id', 'advertisement_id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getMaterialPathAttribute()
    {
        if($this->file_path)
            return asset($this->file_path);
        return asset('/images/no-image-available.png');
    } 

    public function getSiteScreenProductsAttribute()
    {
        $ids = $this->getScreens()->pluck('pi_product_id');
        $site_screen_products = SiteScreenProductViewModel::whereIn('id', $ids)->get();

        if($site_screen_products)
            return $site_screen_products;
        return null;
    } 

    public function getAdvertisementNameAttribute()
    {
        $advertisment = $this->getAdvertisement()->first();
        if($advertisment)
            return $advertisment->name;
        return null;
    }

    public function getCompanyNameAttribute()
    {
        $advertisment = $this->getAdvertisement()->first();
        if($advertisment)
            return $advertisment->company_name;
        return null;
    }

    public function getBrandNameAttribute()
    {
        $advertisment = $this->getAdvertisement()->first();
        if($advertisment)
            return $advertisment->brand_name;
        return null;
    }

    public function getDisplayDurationAttribute()
    {
        $advertisment = $this->getAdvertisement()->first();
        if($advertisment)
            return $advertisment->display_duration;
        return null;
    }
    
    public function getScreenAssignedAttribute()
    {
        $screens = $this->site_screen_products->pluck('site_screen_location')->toArray();
        if($screens)
            return $screens;
        return null;
    }


}
