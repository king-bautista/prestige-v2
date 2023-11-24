<?php

namespace App\Models\AdminViewModels;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Models\SiteScreenProduct;
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
        'ad_type_name',
        'advertisement_details',
        'material_details',
        'thumbnail_path',
        'content_details',
        'content_serial_number',
        'brand_details',
        'brand_name',
        'category_details',
        'brand_name',
        'category_name',
        'parent_category_name',
        'company_name',
        'start_date',
        'end_date',
        'active',
        'duration',
        'display_duration',
    ];

    public function getAdvertisementDetails() {
        return $this->hasOne('App\Models\Advertisement', 'id', 'advertisement_id');
    }

    public function getContentDetails() {
        return $this->hasOne('App\Models\ContentManagement', 'id', 'content_id');
    }

    public function getBrandDetails()
    {   
        return $this->hasOne('App\Models\Brand', 'id', 'brand_id');
    }

    public function getCompanyDetails() {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    public function getCategoryDetails()
    {   
        $category = $this->hasOne('App\Models\Category', 'id', 'category_id')->first();
        $parent_category = Category::find($category->parent_id);
        return [
            'category' => $category,
            'parent_category' => $parent_category
        ];
    }

    public function getMaterialDetails() {
        return AdvertisementMaterial::where('advertisement_id', $this->advertisement_id)->where('dimension', $this->dimension)->first();
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getAdTypeNameAttribute() 
    {
        $screen_product = SiteScreenProduct::where('dimension', $this->dimension)->first();
        if($screen_product)
            return $screen_product->ad_type;
        return null;
    }

    public function getAdvertisementDetailsAttribute() 
    {
        $advertisement = $this->getAdvertisementDetails()->first();
        if($advertisement)
            return $advertisement;
        return null;
    }

    public function getMaterialDetailsAttribute() 
    {
        $material = $this->getMaterialDetails();
        if($material)
            return $material;
        return null;
    }

    public function getThumbnailPathAttribute() 
    {
        if($this->material_details)
            return asset($this->material_details->thumbnail_path);
        return asset('/images/no-image-available.png');
    }

    public function getContentDetailsAttribute() 
    {
        $content = $this->getContentDetails()->first();
        if($content)
            return $content;
        return null;
    }

    public function getContentSerialNumberAttribute() 
    {
        if($this->content_details)
            return $this->content_details->serial_number;
        return null;
    }

    public function getBrandDetailsAttribute()
    {
        $brand = $this->getBrandDetails()->first();
        if($brand)
            return $brand;
        return null;
    }

    public function getBrandNameAttribute()
    {
        if($this->brand_details)
            return $this->brand_details->name; 
        return null;
    }

    public function getCategoryDetailsAttribute()
    {
        if($this->getCategoryDetails())
            return $this->getCategoryDetails();
        return null;
    }

    public function getCategoryNameAttribute() 
    {
        if($this->category_details)
            return $this->category_details['category']['name'];
        return null;
    }

    public function getParentCategoryNameAttribute() 
    {
        if($this->category_details['parent_category'])
            return $this->category_details['parent_category']['name'];
        return null;
    }

    public function getCompanyNameAttribute() 
    {
        $company_details = $this->getCompanyDetails()->first();
        if($company_details)
            return $company_details->name;
        return null;
    }

    public function getStartDateAttribute() 
    {
        if($this->content_details)
            return $this->content_details->start_date;
        return null;
    }

    public function getEndDateAttribute() 
    {
        if($this->content_details)
            return $this->content_details->end_date;
        return null;
    }
    
    public function getActiveAttribute() 
    {
        if($this->content_details)
            return $this->content_details->active;
        return null;
    }

    public function getDurationAttribute()
    {
        $start_date = Carbon::parse($this->content_details->start_date);
        $end_date = Carbon::parse($this->content_details->end_date);    

        $total_days = $start_date->diffInDays($end_date);
        if($total_days)
            return $total_days. ' Days';
        return 0;
    }

    public function getDisplayDurationAttribute() 
    {
        if($this->advertisement_details)
            return $this->advertisement_details->display_duration;
        return null;
    }
    
}
