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
        'brand_name',
        'advertisement_name',
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
        return null;
    }    

    public function getSiteNameAttribute() 
    {
        $site = Site::find($this->site_id);
        if($site)
            return $site['name'];
        return null;
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
}
