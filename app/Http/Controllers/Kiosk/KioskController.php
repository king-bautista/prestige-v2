<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Category;
use App\Models\AdminViewModels\SiteViewModel;
use App\Models\ViewModels\SiteCategoryViewModel;
use App\Models\ViewModels\SiteTenantViewModel;

class KioskController extends AppBaseController
{
    public function index($site_name = null)
    {
        $site = SiteViewModel::when(!$site_name, function($query) {
            $query->where('is_default', 1);
        })
        ->when($site_name, function($query) use($site_name) {
            $query->whereRaw('REPLACE(LOWER(name), " ", "-") = ?', [$site_name]);
        })
        ->where('active', 1)->first(); 

        if(!$site)
            return view('kiosk.page-not-found');

        // GET ALPHABETICAL TENANTS
        // GET TENANTS PER SUB-CATEGORIES
        // GET SUPLEMENTALS
        // GET TENANTS BY SUPPLEMENTALS
        $categories = $this->getCategories($site->id);
        //return $this->getTenants($site->id, 1);

        $template_name = str_replace("-", "_", strtolower($site_name));
        return view('kiosk.'.$template_name.'.main', compact('site', 'categories'));

        // GET PLAYLIST
        // MAP
    }

    public function getCategories($site_id= 0) {
        $new_categories = [];
        $categories = SiteCategoryViewModel::where('site_id', $site_id)->whereNull('sub_category_id')->get();
        if($categories) {
            foreach($categories as $index => $category) {
                $category = json_decode($category, TRUE);
                $category['sub_categories'] = $this->getChildCategories($site_id, $category['category_id']);
                $category['alphabetical'] = $this->getTenants($site_id, $category['category_id']);
                $category['supplemental'] = $this->getSupplemental($site_id, $category['category_id']);
                $new_categories[] = $category;
            }
        }

        return json_encode($new_categories);
    }

    public function getChildCategories($site_id, $category_id) {
        $child_categories = [];
        if (config('app.env') == 'local') { 
            $categories = SiteCategoryViewModel::where('site_id', $site_id)
            ->where('category_id', $category_id)
            ->whereNotNull('sub_category_id')
            ->get();
        }
        else {
            $categories = SiteCategoryViewModel::where('company_categories.site_id', $site_id)
            ->where('company_categories.category_id', $category_id)
            ->where('site_tenants.active', 1)
            ->whereNotNull('company_categories.sub_category_id')
            ->join('brands', 'company_categories.sub_category_id', '=', 'brands.category_id')
            ->join('site_tenants', 'brands.id', '=', 'site_tenants.brand_id')
            ->join('site_points', 'site_tenants.id', '=', 'site_points.tenant_id')
            ->select('company_categories.*')
            ->distinct()
            ->get();
        }

        foreach($categories as $index => $category) {
            $category['tenants'] = $this->getTenants($site_id, $category['category_id'], $category['sub_category_id']);
            $child_categories[] = $category;
        }

        return $child_categories;
    }

    public function getSupplemental($site_id, $category_id) {
        $supplemental_category = Category::where('supplemental_category_id', $category_id)->first();
        $supplemental['name'] = $supplemental_category->name;
        $supplemental['sub_categories'] = $this->getChildCategories($site_id, $supplemental_category->id);

        return $supplemental;
    }

    public function getTenants($site_id = null, $parent_category_id = null, $category_id = null) {
        $tenants = SiteTenantViewModel::where('site_tenants.site_id', $site_id)
        ->where('categories.parent_id', $parent_category_id)
        ->when($category_id, function($query) use($category_id) {
            $query->where('categories.id', $category_id);
        })
        ->where('site_tenants.active', 1)
        ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
        ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
        ->leftJoin('site_tenant_metas', function($join)
        {
            $join->on('site_tenants.id', '=', 'site_tenant_metas.site_tenant_id')
            ->where('site_tenant_metas.meta_key', 'address');
        })
        ->when(config('app.env') == 'prod', function($query) {
            $query->join('site_points', 'site_tenants.id', '=', 'site_points.tenant_id');
        })
        ->select('site_tenants.*', 'site_tenant_metas.meta_value as address')
        ->orderBy('brands.name', 'ASC')
        ->orderBy('site_tenants.site_building_level_id', 'ASC')
        ->orderBy('address', 'ASC')
        ->get()->toArray();

        $tenants = array_chunk($tenants, 15);
        return $tenants;
    }

}
