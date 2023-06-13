<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Company;
use App\Models\Category;
use App\Models\Site;

class CompanyCategoryViewModel extends Model
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
    protected $table = 'company_categories';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $appends = [
        'kiosk_image_primary_path',
        'kiosk_image_top_path',
        'company_name',
        'category_name',
        'sub_category_name',
        'site_name',
    ]; 

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getKioskImagePrimaryPathAttribute()
    {
        if($this->kiosk_image_primary)
            return asset($this->kiosk_image_primary);
        return asset('/images/no-image-available.png');
    } 

    public function getKioskImageTopPathAttribute()
    {
        if($this->kiosk_image_top)
            return asset($this->kiosk_image_top);
        return asset('/images/no-image-available.png');
    } 

    public function getCompanyNameAttribute()
    {
        $company = Company::find($this->company_id);
        if($company)
            return $company['name'];
        return null;
    }
    
    public function getCategoryNameAttribute()
    {
        $category = Category::find($this->category_id);
        if($category)
            return $category['name'];
        return null;
    } 

    public function getSubCategoryNameAttribute()
    {
        $category = Category::find($this->sub_category_id);
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
    
}
