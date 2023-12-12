<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\AdminViewModels\SiteViewModel;
use App\Models\ViewModels\SiteCategoryViewModel;

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

        $categories = $this->getCategories($site->id);

        $template_name = str_replace("-", "_", strtolower($site_name));
        return view('kiosk.'.$template_name.'.main', compact('site', 'categories'));

        // GET PLAYLIST

        // GET CATEGORIES
            // GET SUB-CATEGORIES
            // GET TENANTS PER SUB-CATEGORIES
            // GET ALPHABETICAL TENANTS
            // GET SUPLEMENTALS
            // GET TENANTS BY SUPPLEMENTALS

        // MAP



    }

    public function getCategories($site_id= 0) {
        $new_categories = [];
        $categories = SiteCategoryViewModel::where('site_id', $site_id)->whereNull('sub_category_id')->get();
        if($categories) {
            foreach($categories as $index => $category) {
                $category = json_decode($category, TRUE);
                $child_categories = SiteCategoryViewModel::where('site_id', $site_id)->where('category_id', $category['category_id'])->whereNotNull('sub_category_id')->get();
                $category['children'] = $child_categories;
                $new_categories[] = $category;
            }
        }

        return json_encode($new_categories);
    }

}
