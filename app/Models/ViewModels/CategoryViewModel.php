<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Category;
use App\Models\CategoryLabel;
use App\Models\CompanyCategory;

class CategoryViewModel extends Model
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
    protected $table = 'categories';

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
        'parent_category',
        'supplemental_category_name',
        'type_category_name',
        'children',
        'label',
        'kiosk_image_primary_path',
        'supplemental',
    ];

    public static $site_id = '';
    public static $company_id = '';

    public function getChildCategories()
    {   
        return $this->hasMany('App\Models\ViewModels\CategoryViewModel', 'parent_id', 'id');
    }

    public static function getMainCategory($site_id = 0, $company_id = 0)
    {
        self::$site_id = $site_id;
        self::$company_id = $company_id;

        return self::whereNull('parent_id')->where('category_type', 1)->where('active', 1)->get();
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/    
    public function getParentCategoryAttribute() 
    {
        $parent_category = Category::find($this->parent_id);
        if($parent_category)
            return $parent_category['name'];
        return null;
    }

    public function getSupplementalCategoryNameAttribute() 
    {

        $supplemental_category = Category::find($this->supplemental_category_id);
        if($supplemental_category)
            return $supplemental_category['name'];

        $parent_supplimental = Category::find($this->parent_id);
        if($parent_supplimental) {
            $supplemental_category = Category::find($parent_supplimental->supplemental_category_id);
            if($supplemental_category)
                return $supplemental_category['name'];
        }
        
        return null;
    }

    public function getTypeCategoryNameAttribute() 
    {
        if($this->category_type === 1)
            return 'Category - '.$this->name;
        return 'Supplemental - '.$this->name;        
    }

    public function getChildrenAttribute() 
    {

        $child_categories = $this->getChildCategories()->get();
        if($child_categories)
            return $child_categories;
        return null;
    }

    public function getLabelAttribute() 
    {
        // SITE AND COMPANY LABEL
        if(self::$company_id && self::$site_id) {
            $category_label = CategoryLabel::where('category_id', $this->id)->where('site_id', self::$site_id)->where('company_id', self::$company_id)->first();
            if($category_label) 
                return $category_label['name'];
        }

        // SITE LABEL
        if(self::$site_id) {
            $category_label = CategoryLabel::where('category_id', $this->id)->where('site_id', self::$site_id)->first();
            if($category_label) 
                return $category_label['name'];
        }

        // COMPANY LABEL
        if(self::$company_id) {
            $category_label = CategoryLabel::where('category_id', $this->id)->where('company_id', self::$company_id)->first();
            if($category_label) 
                return $category_label['name'];
        }

        return $this->name;
    }

    public function getKioskImagePrimaryPathAttribute() 
    {
        // DEFAULT MAIN CATEGORY IMAGE
        // SITE ID AND COMPANY ID EXIST
        if(self::$site_id && self::$company_id) {
            $company_categories = CompanyCategoryViewModel::where('category_id', $this->id)
                                ->whereNull('sub_category_id')
                                ->where('site_id', self::$site_id)
                                ->where('company_id', self::$company_id)
                                ->first();
            if($company_categories) 
                return asset($company_categories['kiosk_image_primary']);
        }

        // SITE PRIMARY IMAGE
        if(self::$site_id) {
            $company_categories = CompanyCategoryViewModel::where('category_id', $this->id)
                                ->whereNull('sub_category_id')
                                ->where('site_id', self::$site_id)
                                ->first();
            if($company_categories) 
                return asset($company_categories['kiosk_image_primary']);
        }

        // COMPANY PRIMARY IMAGE
        if(self::$company_id) {
            $company_categories = CompanyCategoryViewModel::where('category_id', $this->id)
                                ->whereNull('sub_category_id')
                                ->where('company_id', self::$company_id)
                                ->first();
            if($company_categories) 
                return asset($company_categories['kiosk_image_primary']);
        }

        $company_categories = CompanyCategoryViewModel::where('category_id', $this->id)
                            ->whereNull('sub_category_id')
                            ->whereNull('site_id')->whereNull('company_id')
                            ->first();
        if($company_categories) 
            return asset($company_categories['kiosk_image_primary']);

        // SUB CATEGORY IMAGE
        // SITE ID AND COMPANY ID EXIST
        if(self::$site_id && self::$company_id) {
            $company_categories = CompanyCategoryViewModel::where('sub_category_id', $this->id)
                                  ->where('site_id', self::$site_id)
                                  ->where('company_id', self::$company_id)
                                  ->first();
            if($company_categories) 
                return asset($company_categories['kiosk_image_primary']);
        }

        // SITE PRIMARY IMAGE
        if(self::$site_id) {
            $company_categories = CompanyCategoryViewModel::where('sub_category_id', $this->id)
                                  ->where('site_id', self::$site_id)
                                  ->first();
            if($company_categories) 
                return asset($company_categories['kiosk_image_primary']);
        }

        // COMPANY PRIMARY IMAGE
        if(self::$company_id) {
            $company_categories = CompanyCategoryViewModel::where('sub_category_id', $this->id)
                                  ->where('company_id', self::$company_id)
                                  ->first();
            if($company_categories) 
                return asset($company_categories['kiosk_image_primary']);
        }

        // DEFAULT SUB CATEGORY IMAGE
        $company_categories = CompanyCategoryViewModel::where('sub_category_id', $this->id)
                            ->whereNull('site_id')
                            ->whereNull('company_id')
                            ->first();
        if($company_categories) 
            return asset($company_categories['kiosk_image_primary']);
        
        return asset('/images/no-image-available.png');
    }

    public function getSupplementalAttribute() 
    {
        $supplemental = Category::where('supplemental_category_id', $this->id)->first();
        if($supplemental) {
            $child_array = CategoryViewModel::where('parent_id', $supplemental['id'])->where('active', 1)->get()->toArray();
            $supplemental['children'] = array_chunk($child_array, 15);
            return $supplemental;
        }
        return null;
    }
}
