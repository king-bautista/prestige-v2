<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Category;
use App\Models\CategoryLabel;
use App\Models\SiteScreen;
use App\Models\SiteTenant;

class DirectoryCategoryViewModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'category_type',
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

     /**
     * Append additiona info to the return data
     *
     * @var string
     */
	public $appends = [
        'label',
        'category_id',
        'category_name',
        'parent_category_id',
        'parent_category_name',
        'main_category_id',
        'kiosk_image_primary_path',
        'kiosk_image_top_path',
        'children',
        'alphabetical',
        'supplemental',
    ];

    public static $site_id = '';
    public static $company_id = '';

    public static function getMainCategory($site_id = 0, $company_id = 0)
    {
        self::$site_id = $site_id;
        self::$company_id = $company_id;

        return self::whereNull('parent_id')->where('category_type', 1)->where('active', 1);
    }

    public function getParentCategory()
    {
        $parent_category = Category::find($this->parent_id);
        if($parent_category)
            return $parent_category;
        return null;
    }

    public function getChildCategories()
    {   
        $categories = $this->hasMany('App\Models\ViewModels\DirectoryCategoryViewModel', 'parent_id', 'id');
        return $categories;
    }

    public function getTenants()
    {
        $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();            
        $site_screen = SiteScreen::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first();            
        $elevator_ids = SiteTenant::where('brand_id', 7786)->where('site_building_level_id', '!=', $site_screen->site_building_level_id)->get()->pluck('id');

        if (config('app.env') == 'local') {
            $tenants = DirectorySiteTenantViewModel::where('site_tenants.active', 1)
                ->where('categories.parent_id', $this->id)
                ->where('site_tenants.site_id', self::$site_id)
                ->whereNotIn('site_tenants.id', $elevator_ids)
                ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
                ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
                ->leftJoin('site_tenant_metas', function($join)
                {
                    $join->on('site_tenants.id', '=', 'site_tenant_metas.site_tenant_id')
                            ->where('site_tenant_metas.meta_key', 'address');
                })
                ->select('site_tenants.*', 'site_tenant_metas.meta_value as address')
                ->orderBy('brands.name', 'ASC')
                ->orderBy('site_tenants.site_building_level_id', 'ASC')
                ->orderBy('address', 'ASC')
                ->get()->toArray();
        
            if($tenants) {
                if($site_screen->orientation == 'Portrait') {
                    $tenants = array_chunk($tenants, 15);
                }
                else {
                    $tenants = array_chunk($tenants, 12);
                }

                return $tenants;
            }
            return null;
        } else {
            $tenants = DirectorySiteTenantViewModel::where('site_tenants.active', 1)
                ->where('categories.parent_id', $this->id)
                ->where('site_tenants.site_id', self::$site_id)
                ->whereNotIn('site_tenants.id', $elevator_ids)
                ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
                ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
                ->join('site_points', 'site_tenants.id', '=', 'site_points.tenant_id')
                ->leftJoin('site_tenant_metas', function($join)
                {
                    $join->on('site_tenants.id', '=', 'site_tenant_metas.site_tenant_id')
                            ->where('site_tenant_metas.meta_key', 'address');
                })
                ->select('site_tenants.*', 'site_tenant_metas.meta_value as address')
                ->orderBy('brands.name', 'ASC')
                ->orderBy('site_tenants.site_building_level_id', 'ASC')
                ->orderBy('address', 'ASC')
                ->distinct()
                ->get()->toArray();
        
            if($tenants) {
                if($site_screen->orientation == 'Portrait') {
                    $tenants = array_chunk($tenants, 15);
                }
                else {
                    $tenants = array_chunk($tenants, 12);
                }
                return $tenants;
            }
            return null;
        }
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/ 
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

    public function getCategoryIdAttribute() 
    {
        return $this->id;
    }

    public function getCategoryNameAttribute() 
    {
        return $this->name;
    }

    public function getParentCategoryIdAttribute() 
    {
        $parent_category = $this->getParentCategory();
        if($parent_category)
            return $parent_category['id'];
        return null;
    }

    public function getParentCategoryNameAttribute() 
    {
        $parent_category = $this->getParentCategory();
        if($parent_category)
            return $parent_category['name'];
        return null;
    }

    public function getMainCategoryIdAttribute() 
    {
        $parent_category = $this->getParentCategory();
        $parent_category_id = null;

        if($parent_category && !isset($parent_category['supplemental_category_id']))
            return $parent_category['id'];

        if(isset($parent_category['supplemental_category_id']))
            return Category::find($parent_category['supplemental_category_id'])->id;

        return null;
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

    public function getKioskImageTopPathAttribute() 
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
                return asset($company_categories['kiosk_image_top']);
        }

        // SITE PRIMARY IMAGE
        if(self::$site_id) {
            $company_categories = CompanyCategoryViewModel::where('category_id', $this->id)
                                ->whereNull('sub_category_id')
                                ->where('site_id', self::$site_id)
                                ->first();
            if($company_categories) 
                return asset($company_categories['kiosk_image_top']);
        }

        // COMPANY PRIMARY IMAGE
        if(self::$company_id) {
            $company_categories = CompanyCategoryViewModel::where('category_id', $this->id)
                                ->whereNull('sub_category_id')
                                ->where('company_id', self::$company_id)
                                ->first();
            if($company_categories) 
                return asset($company_categories['kiosk_image_top']);
        }

        $company_categories = CompanyCategoryViewModel::where('category_id', $this->id)
                            ->whereNull('sub_category_id')
                            ->whereNull('site_id')->whereNull('company_id')
                            ->first();
        if($company_categories) 
            return asset($company_categories['kiosk_image_top']);

        // SUB CATEGORY IMAGE
        // SITE ID AND COMPANY ID EXIST
        if(self::$site_id && self::$company_id) {
            $company_categories = CompanyCategoryViewModel::where('sub_category_id', $this->id)
                                  ->where('site_id', self::$site_id)
                                  ->where('company_id', self::$company_id)
                                  ->first();
            if($company_categories) 
                return asset($company_categories['kiosk_image_top']);
        }

        // SITE PRIMARY IMAGE
        if(self::$site_id) {
            $company_categories = CompanyCategoryViewModel::where('sub_category_id', $this->id)
                                  ->where('site_id', self::$site_id)
                                  ->first();
            if($company_categories) 
                return asset($company_categories['kiosk_image_top']);
        }

        // COMPANY PRIMARY IMAGE
        if(self::$company_id) {
            $company_categories = CompanyCategoryViewModel::where('sub_category_id', $this->id)
                                  ->where('company_id', self::$company_id)
                                  ->first();
            if($company_categories) 
                return asset($company_categories['kiosk_image_top']);
        }

        // DEFAULT SUB CATEGORY IMAGE
        $company_categories = CompanyCategoryViewModel::where('sub_category_id', $this->id)
                            ->whereNull('site_id')
                            ->whereNull('company_id')
                            ->first();
        if($company_categories) 
            return asset($company_categories['kiosk_image_top']);
        
        return asset('/images/no-image-available.png');
    }

    public function getChildrenAttribute() 
    {
        if (config('app.env') == 'local') {
            $child_categories = DirectoryCategoryViewModel::where('parent_id', $this->id)
                                ->where('active', 1)
                                ->whereNull('category_labels.deleted_at')
                                ->leftJoin('category_labels', 'categories.id', '=', 'category_labels.category_id')
                                ->selectRaw("(case when category_labels.name != '' then category_labels.name ELSE categories.name END) AS cat_label, categories.*")
                                ->orderBy('cat_label')
                                ->distinct()
                                ->get();
        } else {
            $child_categories = DirectoryCategoryViewModel::where('parent_id', $this->id)
                                ->where('categories.active', 1)
                                ->whereNull('category_labels.deleted_at')
                                ->leftJoin('category_labels', 'categories.id', '=', 'category_labels.category_id')
                                ->join('brands', 'categories.id', '=', 'brands.category_id')
                                ->join('site_tenants', 'brands.id', '=', 'site_tenants.brand_id')
                                ->selectRaw("(case when category_labels.name != '' then category_labels.name ELSE categories.name END) AS cat_label, categories.*")
                                ->orderBy('cat_label')
                                ->distinct()
                                ->get();
        }

        if($child_categories)
            return $child_categories;
        return null;
    }

    public function getSupplementalAttribute() 
    {
        $supplemental = Category::where('supplemental_category_id', $this->id)->first();
        if (config('app.env') == 'local') {
            if($supplemental) {
                $child_array = DirectoryCategoryViewModel::where('parent_id', $supplemental['id'])->where('active', 1)->orderBy('name', 'ASC')->get()->toArray();
                $supplemental['children'] = array_chunk($child_array, 15);
                return $supplemental;
            }
        } else {
            if($supplemental) {
                $child_array = DirectoryCategoryViewModel::where('parent_id', $supplemental['id'])
                ->leftJoin('brand_supplementals', 'brand_supplementals.supplemental_id', '=', 'categories.id')
                ->leftJoin('site_tenants', 'site_tenants.brand_id', '=', 'brand_supplementals.brand_id')
                ->where('site_tenants.site_id', 1)
                ->select('categories.*')
                ->groupBy('name')
                ->orderBy('name', 'ASC')
                ->get()
                ->toArray();
                $supplemental['children'] = array_chunk($child_array, 15);
                return $supplemental;
            }
        }    
        return null;
    }

    public function getAlphabeticalAttribute() 
    {
        return $this->getTenants();
    }

}
