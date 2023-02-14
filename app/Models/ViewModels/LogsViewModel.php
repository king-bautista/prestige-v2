<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Site;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Advertisement;

class LogsViewModel extends Model
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
    protected $table = 'logs';

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
        'site_name',
        'category_name',
        'category_parent_name',
        'brand_logo',
        'brand_name',
        'advertisement_name',
        'main_category_name',
    ];
    
    public function getCategoryNameAttribute() 
    {
        $category_name = Category::find($this->category_id);
        if($category_name)
            return $category_name['name'];
        return null;
    }

    public function getCategoryParentNameAttribute() 
    {
        $category = Category::find($this->parent_category_id);
        if($category)
            return $category['name'];

        $category = Category::where('supplemental_category_id', $this->parent_category_id)->first();
        if($category)
            return $category['name'];

        return null;
    }    

    public function getSiteNameAttribute() 
    {
        $site = Site::find($this->site_id);
        if($site)
            return $site['name'];
        return null;
    }

    public function getBrandLogoAttribute() 
    {
        $logo = Brand::find($this->brand_id)->logo;
        if($logo)
            return asset($logo);
        return asset('/images/no-image-available.png');
    }

    public function getBrandNameAttribute() 
    {
        $brand = Brand::find($this->brand_id);
        if($brand)
            return $brand['name'];
        return null;
    }

    public function getAdvertisementNameAttribute() 
    {
        $advertisement = Advertisement::find($this->advertisement_id);
        if($advertisement)
            return $advertisement['name'];
        return null;
    }

    // public function getMainCategoryIdAttribute() 
    // {
    //     $main_category = Category::find($this->parent_category_id);
    //     if($main_category)
    //         return $main_category['id'];
        
    //     $main_category = Category::where('supplemental_category_id', $this->parent_category_id)->first();
    //     if($main_category)
    //         return $main_category['id'];

    //     return null;
    // }

    public function getMainCategoryNameAttribute() 
    {
        $main_category = null;
        $category = Category::find($this->parent_category_id);
        if($category)
            $main_category = Category::find($category->parent_id);

        if($category && $main_category)
            return $main_category['name'];
        
        if($category && !$main_category)
            return $category['name'];
        
        // $supplemental_category = Category::find($this->parent_category_id)->first();
        // $main_supplemental_category = Category::where('supplemental_category_id', $supplemental_category->parent_category_id)->first();

        // if($supplemental_category && $main_supplemental_category)
        //     return $main_supplemental_category['name'];

        // if($supplemental_category && !$main_supplemental_category)
        //     return $supplemental_category['name'];            
            
        return null;
    }
}
