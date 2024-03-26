<?php

namespace App\Models\AdminViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrandProductViewModel extends Model
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
    protected $table = 'brand_products_promos';

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
        'thumbnail_path',
        'image_url_path',
        'banner_promo_thumbnail_path',
        'banner_promo_image_url_path',
        'brand_name',
    ]; 

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getThumbnailPathAttribute()
    {
        if($this->thumbnail)
            return asset($this->thumbnail);
        return null;
    }  

    public function getImageUrlPathAttribute()
    {
        if($this->image_url)
            return asset($this->image_url);
        return null;
    }
    
    public function getBannerPromoThumbnailPathAttribute()
    {
        if($this->banner_promo_thumbnail)
            return asset($this->banner_promo_thumbnail);
        return null;
    }  

    public function getBannerPromoImageUrlPathAttribute()
    {
        if($this->banner_promo_image_url)
            return asset($this->banner_promo_image_url);
        return null;
    }

    public function getBrandNameAttribute() 
    {     
        $brand = BrandViewModel::find($this->brand_id);
        if($brand)
            return $brand->name;
        return '';
       // return $this->brand_details->name;
    }
}
