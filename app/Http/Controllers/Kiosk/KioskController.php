<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Category;
use App\Models\BrandTag;
use App\Models\Tag;
use App\Models\AdminViewModels\SiteViewModel;
use App\Models\AdminViewModels\CinemaScheduleViewModel;
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

        $site_schedule = json_encode($site->operational_hours);
        $categories = $this->getCategories($site->id);
        $promos = $this->getPromos($site->id);
        $cinemas = $this->getCinemas($site->id);
        $now_showing = $this->getShowing($site->id);
        $suggestions = $this->getSuggestionList($site->id);

        $template_name = str_replace("-", "_", strtolower($site_name));
        return view('kiosk.'.$template_name.'.main', compact('site', 'site_schedule', 'categories', 'promos', 'cinemas', 'now_showing', 'suggestions'));

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
            if($category->category_type == 1) {
                $category['tenants'] = $this->getTenants($site_id, $category['category_id'], $category['sub_category_id']);
            }
            else {
                $category['tenants'] = $this->getTenantsBySupplementals($site_id, $category['sub_category_id']);
            }
            $child_categories[] = $category;
        }

        return $child_categories;

    }

    public function getSupplemental($site_id, $category_id) {

        $supplemental_category = Category::where('supplemental_category_id', $category_id)->first();
        $supplemental['name'] = $supplemental_category->name;
        $sub_categories = $this->getChildCategories($site_id, $supplemental_category->id);
        $supplemental['sub_categories'] = array_chunk($sub_categories, 15);

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

    public function getTenantsBySupplementals($site_id = null, $supplemental_id = null) {

        $site_tenants = SiteTenantViewModel::where('site_tenants.active', 1)
        ->where('brand_supplementals.supplemental_id', $supplemental_id)
        ->where('site_tenants.site_id', $site_id)
        ->join('brand_supplementals', 'site_tenants.brand_id', '=', 'brand_supplementals.brand_id')
        ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
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
            
        $site_tenants = array_chunk($site_tenants, 15);
        return $site_tenants;

    }

    public function getPromos($site_id) {

        $current_date = date('Y-m-d');

        $promos = SiteTenantViewModel::where('site_tenants.site_id', $site_id)
        ->where('brand_products_promos.type', 'promo')
        ->whereDate('brand_products_promos.date_from', '<=', $current_date)
        ->whereDate('brand_products_promos.date_to', '>=', $current_date)
        ->join('site_tenant_products', 'site_tenants.id', '=', 'site_tenant_products.site_tenant_id')
        ->join('brand_products_promos', 'site_tenant_products.brand_product_promo_id', '=', 'brand_products_promos.id')
        ->select('site_tenants.*', 'brand_products_promos.image_url', 'brand_products_promos.id as promo_id')
        ->get()->toArray();

        $promos = array_chunk($promos, 6);
        return json_encode($promos);

    }

    public function getCinemas($site_id) {

        $cinemas = SiteTenantViewModel::where('site_tenants.active', 1)
        ->where('brands.name', 'like', '%CINEMA%')
        ->where('brands.name', 'not like', '%LOBBY%')
        ->where('categories.name', 'like', '%Amusement & Exhibitions%')
        ->where('site_tenants.site_id', $site_id)
        ->join('brands', 'site_tenants.brand_id', '=', 'brands.id')
        ->join('categories', 'brands.category_id', '=', 'categories.id')
        ->select('site_tenants.*')
        ->distinct()
        ->orderBy('brands.name', 'ASC')
        ->get()->toArray();
        
        $cinemas = array_chunk($cinemas, 12);
        return json_encode($cinemas);

    }

    public function getShowing($site_id) {

        $start_date =  date('Y-m-d 00:00:00');
        $end_date =  date('Y-m-d 23:59:59');
        
        $now_showing = CinemaScheduleViewModel::where('show_time', '>=', $start_date)
        ->where('show_time', '<=', $end_date)
        ->where('site_id', $site_id)
        ->select('site_id', 'film_id', 'title', 'rating', 'casting', 'screen_name', 'trailer_url', 'genre', 'synopsis')
        ->groupBy('film_id')
        ->orderBy('title')
        ->get()->toArray();
        
        $now_showing = array_chunk($now_showing, 3);
        return json_encode($now_showing);

    }

    public function getSuggestionList($site_id) {

        $tenants = SiteTenantViewModel::where('site_tenants.site_id', $site_id)
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
        ->orderBy('address', 'ASC');

        $brand_ids = $tenants;
        $brand_ids = $brand_ids->groupBy('site_tenants.brand_id')->get()->pluck('brand_id');

        $site_tenants = $tenants->get();

        $collection = collect([]);

        foreach ($site_tenants as $key => $value) {
            if (in_array($value->brand_id,$brand_ids->toArray())) {
                $collection->push([
                    'id' => $value->id,
                    'value' => $value->brand_name, // . ", " . $value->floor_name . ", " . $value->building_name,
                    'floor_name' => $value->floor_name,
                    'building_name' => $value->building_name,
                    'address' => $value->address,
                    'orderby' => $value->brand_name. ", " . $value->floor_name . ", " . $value->address,
                ]);
            }
            else {
                $collection->push([
                    'id' => $value->id,
                    'value' => $value->name,
                    'floor_name' => null,
                    'building_name' => null,
                    'address' => null,
                    'orderby' => $value->name,
                ]);
            }
        }

        $brand_tags = BrandTag::whereIn('brand_tags.brand_id', $brand_ids)
            ->select('brand_tags.tag_id')
            ->get()
            ->pluck('tag_id');

        $tags = Tag::whereIn('tags.id',  $brand_tags)
            ->select('tags.name')
            ->orderBy('tags.name', 'ASC')
            ->get()
            ->pluck('name');

        foreach ($tags as $key => $value) {
            $value = filter_var(htmlentities(preg_replace("/\r\n|\r|\n|\t/", ' ', $value)), FILTER_SANITIZE_STRING);

            $collection->push([
                'id' => null,
                'value' => addslashes($value),
                'floor_name' => null,
                'building_name' => null,
                'orderby' => addslashes($value),
            ]);
        }

        return json_encode($collection->values()->all());
    }

    public function search(Request $request) {
        // try
        // {
            if (!$request->id) {
                $keyword = preg_replace('!\s+!', ' ', $request->key_words);   

                $tenants = SiteTenantViewModel::where('site_tenants.site_id', $request->site_id)
                ->where('site_tenants.active', 1)
                ->where(function ($query) use($keyword) {
                    $query->orWhere('brands.name', 'like', '%'.$keyword.'%')
                    ->orWhere('brands.name', 'like', $keyword.'%') #LAST WORD but start on first letter | #BETWEEN WORDS
                    ->orWhere('brands.name', 'like', '%'.$keyword) #FIRST WORD but start on first letter
                    ->orWhere('categories.name', 'like', '%'.$keyword)
                    ->orWhere('categories.name', 'like', $keyword.'%')
                    ->orWhere('supp.name', 'like', $keyword.'%')
                    ->orWhere('supp.name', 'like', '%'.$keyword)
                    ->orWhere('tags.name', 'like', $keyword.'%')
                    ->orWhere('tags.name', 'like', '%'.$keyword);
                })
                ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
                ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
                ->leftJoin('site_tenant_metas', function($join) {
                    $join->on('site_tenants.id', '=', 'site_tenant_metas.site_tenant_id')
                    ->where('site_tenant_metas.meta_key', 'address');
                })
                ->leftJoin('brand_supplementals', 'site_tenants.brand_id', '=', 'brand_supplementals.brand_id')
                ->leftJoin('categories as supp', 'brand_supplementals.supplemental_id', '=', 'supp.id')
                ->leftJoin('brand_tags', 'brands.id', '=', 'brand_tags.brand_id')
                ->leftJoin('tags', 'brand_tags.tag_id', '=', 'tags.id')
                ->join('site_points', 'site_tenants.id', '=', 'site_points.tenant_id')
                ->select('site_tenants.*', 'brands.category_id as brand_category_id', 'site_tenant_metas.meta_value as address')
                ->distinct()
                ->orderBy('brands.name', 'ASC')
                ->orderBy('site_tenants.site_building_level_id', 'ASC')
                ->orderBy('address', 'ASC')
                ->get();

                $suggest_cat = [];
                foreach ($tenants as $key => $value) {
                    array_push( $suggest_cat, $value->brand_category_id);           
                }
                $suggest_cat = (array_unique($suggest_cat));

                $suggest_subscribers = SiteTenantViewModel::where('site_tenants.is_subscriber',  1)
                ->join('site_tenant_metas', 'site_tenants.id', '=', 'site_tenant_metas.site_tenant_id')
                ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
                ->whereIn('brands.category_id',  $suggest_cat)
                ->select('site_tenants.*')
                ->distinct()
                ->get()->toArray();

                $tenants = array_chunk($tenants->toArray(), 15);                

                return [
                    'tenants' => $tenants,
                    'suggest_subscribers' => $suggest_subscribers,
                ];

            }
            else {

            }

        // }
        // catch (\Exception $e)
        // {
        //     return response([
        //         'message' => 'No Tenants to display!',
        //         'status_code' => 200,
        //     ], 200);
        // } 
    }

}
