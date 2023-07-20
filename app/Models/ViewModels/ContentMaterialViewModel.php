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
        'material_thumbnails_path',
        'advertisement_name',
        'company_name',
        'brand_name',
        'category_name',
        'parent_category_name',
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

    public function getMaterialThumbnailsPathAttribute()
    {
        if($this->thumbnail_path)
            return asset($this->thumbnail_path);
        return asset('/images/no-image-available.png');
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

    public function getCategoryNameAttribute()
    {
        $advertisment = $this->getAdvertisement()->first();
        if($advertisment)
            return $advertisment->category_name;
        return null;
    }

    public function getParentCategoryNameAttribute()
    {
        $advertisment = $this->getAdvertisement()->first();
        if($advertisment)
            return $advertisment->parent_category_name;
        return null;
    }

    public function getDisplayDurationAttribute()
    {
        $advertisment = $this->getAdvertisement()->first();
        if($advertisment)
            return $advertisment->display_duration;
        return null;
    }

}
