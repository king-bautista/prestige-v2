<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Brand;
use App\Models\Company;
use App\Models\Category;

class AdvertisementViewModel extends Model
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
    protected $table = 'advertisements';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $appends = [
        'material_image_path',
        'company_name',
        'company_details',
        'brand_name',
        'brand_details',
        'category_id',
        'category_name',
        'parent_category_id',
        'parent_category_name',
    ]; 

    public function getCompany()
    {   
        $company = Company::find($this->company_id);
        if($company)
            return $company;
        return null;
    }

    public function getBrand()
    {   
        $brand = Brand::find($this->brand_id);
        if($brand)
            return $brand;
        return null;
    }

    public function getCategory()
    {   
        $brand = Brand::find($this->brand_id);
        $category = Category::find($brand->category_id);
        if($category)
            return $category;
        return null;
    }

    public function getParentCategory()
    {   
        $category = $this->getCategory();
        $parent_category = Category::where('parent_id', $category->parent_id)->first();
        if($parent_category)
            return $parent_category;
        return null;
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getMaterialImagePathAttribute()
    {
        if($this->file_path)
            return asset($this->file_path);
        return asset('/images/no-image-available.png');
    } 

    public function getCompanyNameAttribute()
    {
        return $this->getCompany()->name; 
    }

    public function getCompanyDetailsAttribute()
    {
        return $this->getCompany(); 
    }

    public function getBrandNameAttribute()
    {
        return $this->getBrand()->name; 
    }

    public function getBrandDetailsAttribute()
    {
        return $this->getBrand(); 
    }

    public function getCategoryIdAttribute()
    {
        return $this->getCategory()->id; 
    }

    public function getCategoryNameAttribute()
    {
        return $this->getCategory()->name; 
    }

    public function getParentCategoryIdAttribute()
    {
        if($this->getParentCategory())
            return $this->getParentCategory()->id;
        return null; 
    }

    public function getParentCategoryNameAttribute()
    {
        if($this->getParentCategory())
            return $this->getParentCategory()->name;
        return null;
    }
}
