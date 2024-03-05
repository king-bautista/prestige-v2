<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Category;
use App\Models\BrandTag;
use App\Models\Tag;
use App\Models\SiteScreen;
use App\Models\SiteTenant;
use App\Models\SiteMeta;
use App\Models\Amenity;
use App\Models\SiteBuilding;
use App\Models\SiteMapZoom;
use App\Models\Event;
use App\Models\SiteMap;
use App\Models\AdminViewModels\SiteViewModel;
use App\Models\AdminViewModels\CinemaScheduleViewModel;
use App\Models\AdminViewModels\PlayListViewModel;
use App\Models\AdminViewModels\SiteMapConfigViewModel;
use App\Models\AdminViewModels\SiteMapViewModel;
use App\Models\AdminViewModels\SiteBuildingLevelViewModel;
use App\Models\ViewModels\SiteCategoryViewModel;
use App\Models\ViewModels\SiteTenantViewModel;
use App\Models\ViewModels\AssistantMessageViewModel;
use App\Models\ViewModels\TranslationViewModel;
use App\Models\ViewModels\SitePointViewModel;
use App\Models\ViewModels\SitePointLinkViewModel;
use App\Models\ViewModels\SitePointTenantViewModel;

class KioskController extends AppBaseController
{
    public $site = '';

    public function index($site_name = null)
    {
        // try
        // {
            $site = SiteViewModel::when(!$site_name, function($query) {
                $query->where('is_default', 1);
            })
            ->when($site_name, function($query) use($site_name) {
                $query->whereRaw('REPLACE(LOWER(name), " ", "-") = ?', [$site_name]);
            })
            ->where('active', 1)
            ->first(); 

            if(!$site)
                return view('kiosk.page-not-found');

            $this->site = $site;
            
            $template_name = $this->site->details['site_theme'];
            $site_schedule = $this->site->details['schedules'];
            $operational_hours = json_encode($this->site->operational_hours);
            $categories = $this->getCategories();
            $promos = $this->getPromos();
            $events = $this->getEvents();
            $cinemas = $this->getCinemas();
            $now_showing = $this->getShowing();
            $suggestions = $this->getSuggestionList();
            $banner_ads = $this->getBannerAds();
            $fullscreen_ads = $this->getFullScreenAds();
            $assistant_message = $this->getAssistantMessage();
            $translations = $this->getTranslation();
            $all_tenants = $this->getTenants();

            // MAP PAGE DATA
            $site_config = $this->getSiteConfig();
            $site_buildings = $this->getBuildingFloors();

            $building_count = $site_buildings['building'];
            $site_floors = $site_buildings['floors'];

            $site_maps = $this->getSiteMaps();

            $map_points_tenants_links = $this->getMapPointsTenantLinks($site_maps, $all_tenants);
            $map_points = json_encode($map_points_tenants_links['map_points']);
            $map_tenants = json_encode($map_points_tenants_links['map_tenants']);
            $kiosk_zoom = $this->getMapZoom($site_maps, $site_config->view_angle);
            $kiosk_center = $this->getMapCenter($site_maps, $site_config->view_angle, $site_config->origin_point);
            if(count($kiosk_center) == 0)
                $kiosk_center = $this->getMapCenter($site_maps, $site_config->view_angle);

            $kiosk_center = json_encode($kiosk_center);
            $amenities = $this->getAmenities();

            // Sprite configuration
            $icon_version = '4x'; //no names
            $pin_scale_x = 10;
            $pin_scale_y = 10;
            $pin_scale_z = 0;
            $icon_scale_x = 3.5;
            $icon_scale_y = 3.5;
            $icon_scale_z = 0;

            $floor_font_scale_x = 2;
            $floor_font_scale_y = 2;
            $floor_font_scale_z = 2;

            $floor_width = 24;
            $floor_height = 7;

            $spritepinto_height = 10;

            // END MAP PAGE DATA
            $data = [
                'site', 
                'site_schedule',
                'operational_hours', 
                'categories', 
                'promos', 
                'events',
                'cinemas', 
                'now_showing', 
                'suggestions', 
                'banner_ads', 
                'fullscreen_ads', 
                'assistant_message', 
                'translations', 
                'all_tenants', 
                'site_config', 
                'building_count',
                'site_floors',
                'site_maps', 
                'map_points', 
                'map_tenants',
                'kiosk_zoom',
                'kiosk_center',
                'amenities',
                'icon_version',
                'pin_scale_x',
                'pin_scale_y',
                'pin_scale_z',
                'icon_scale_x',
                'icon_scale_y',
                'icon_scale_z',
                'floor_font_scale_x',
                'floor_font_scale_y',
                'floor_font_scale_z',
                'floor_width',
                'floor_height',
                'spritepinto_height',
            ];

            return view('kiosk.'.$template_name.'.main', compact($data));
        // }
        // catch (\Exception $e)
        // {
        //     return view('kiosk.page-not-found');
        // } 
    }

    public function getCategories() {
        $new_categories = [];
        $categories = SiteCategoryViewModel::where('site_id', $this->site->id)
        ->where('active', 1)
        ->whereNull('sub_category_id')
        ->get();
        
        if($categories) {
            foreach($categories as $index => $category) {
                $category = json_decode($category, TRUE);
                $category['sub_categories'] = $this->getChildCategories($category['category_id']);
                $category['alphabetical'] = $this->getTenants($category['category_id']);
                $category['supplemental'] = $this->getSupplemental($category['category_id']);
                $new_categories[] = $category;
            }
        }

        return json_encode($new_categories);

    }

    public function getChildCategories($category_id) {

        $child_categories = [];
        if (config('app.env') == 'local') { 
            $categories = SiteCategoryViewModel::where('site_id', $this->site->id)
            ->where('company_categories.active', 1)
            ->where('company_categories.category_id', $category_id)
            ->whereNotNull('company_categories.sub_category_id')
            ->leftJoin('categories', 'company_categories.sub_category_id', '=', 'categories.id')
            ->orderBy('categories.name')
            ->get();
        }
        else {
            $categories = SiteCategoryViewModel::where('company_categories.site_id', $this->site->id)
            ->where('company_categories.category_id', $category_id)
            ->where('site_tenants.active', 1)
            ->whereNotNull('company_categories.sub_category_id')
            ->join('brands', 'company_categories.sub_category_id', '=', 'brands.category_id')
            ->join('site_tenants', 'brands.id', '=', 'site_tenants.brand_id')
            ->join('site_points', 'site_tenants.id', '=', 'site_points.tenant_id')
            ->leftJoin('categories', 'company_categories.sub_category_id', '=', 'categories.id')
            ->select('company_categories.*')
            ->distinct()
            ->orderBy('categories.name')
            ->get();
        }

        foreach($categories as $index => $category) {
            if($category->category_type == 1) {
                $category['tenants'] = $this->getTenants($category['category_id'], $category['sub_category_id']);
            }
            else {
                $category['tenants'] = $this->getTenantsBySupplementals($category['sub_category_id']);
            }
            $child_categories[] = $category;
        }

        return $child_categories;

    }

    public function getSupplemental($category_id) {

        $supplemental_category = Category::where('supplemental_category_id', $category_id)->first();
        if($supplemental_category) {
            $supplemental['name'] = $supplemental_category->name;
            $sub_categories = $this->getChildCategories($supplemental_category->id);
            $supplemental['sub_categories'] = array_chunk($sub_categories, 15);
    
            return $supplemental;
        }

        return null;
    }

    public function getTenants($parent_category_id = null, $category_id = null) {

        $map_type = '3D';
        SiteTenantViewModel::setSiteId($this->site->id);        

        $site_map_type = SiteMeta::where('site_id', $this->site->id)->where('meta_key', 'map_type')->first();
        if($site_map_type)
            $map_type = $site_map_type->meta_value;

        $tenants = SiteTenantViewModel::where('site_tenants.site_id', $this->site->id)        
        ->when($parent_category_id, function($query) use($parent_category_id) {
            $query->where('categories.parent_id', $parent_category_id);
        })
        ->when($category_id, function($query) use($category_id) {
            $query->where('categories.id', $category_id);
        })
        ->where('site_tenants.active', 1)
        ->where('site_maps.map_type', $map_type)
        ->join('site_points', 'site_tenants.id', '=', 'site_points.tenant_id')
        ->join('site_maps', 'site_points.site_map_id', '=', 'site_maps.id')
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

        if($parent_category_id || $category_id) 
            $tenants = array_chunk($tenants, 15);

        return $tenants;
    }

    public function getTenantsBySupplementals($supplemental_id = null) {

        $site_tenants = SiteTenantViewModel::where('site_tenants.active', 1)
        ->where('brand_supplementals.supplemental_id', $supplemental_id)
        ->where('site_tenants.site_id', $this->site->id)
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

    public function getPromos() {

        $current_date = date('Y-m-d');
        $promos_products = [];

        $promos = SiteTenantViewModel::where('site_tenants.site_id', $this->site->id)
        ->where('site_tenants.active', 1)        
        ->where('brand_products_promos.active', 1)        
        ->where('brand_products_promos.type', 'promo')
        ->whereDate('brand_products_promos.date_from', '<=', $current_date)
        ->whereDate('brand_products_promos.date_to', '>=', $current_date)
        ->join('site_tenant_products', 'site_tenants.id', '=', 'site_tenant_products.site_tenant_id')
        ->join('brand_products_promos', 'site_tenant_products.brand_product_promo_id', '=', 'brand_products_promos.id')
        ->select('site_tenants.*', 'brand_products_promos.image_url', 'brand_products_promos.id as promo_id')
        ->get()->toArray();

        // $products = SiteTenantViewModel::where('site_tenants.site_id', $this->site->id)        
        // ->where('site_tenants.active', 1)        
        // ->where('site_tenants.is_subscriber', 1)        
        // ->where('brand_products_promos.active', 1)        
        // ->where('brand_products_promos.type', 'product')
        // ->join('site_tenant_products', 'site_tenants.id', '=', 'site_tenant_products.site_tenant_id')
        // ->join('brand_products_promos', 'site_tenant_products.brand_product_promo_id', '=', 'brand_products_promos.id')
        // ->select('site_tenants.*', 'brand_products_promos.image_url', 'brand_products_promos.id as promo_id')
        // ->get()->toArray();

        if($promos) {
            foreach($promos as $promo) {
                $promos_products[] = $promo;
            }
        }

        // if($products) {
        //     foreach($products as $product) {
        //         $promos_products[] = $product;
        //     }
        // }

        $promos_products = array_chunk($promos_products, 6);
        return json_encode($promos_products);

    }

    public function getEvents() {
        $current_date = date('Y-m-d');

        $events = Event::where('site_id', $this->site->id)
        ->whereDate('start_date', '<=', $current_date)
        ->whereDate('end_date', '>=', $current_date)
        ->where('active', 1)
        ->get()->toArray();
        $events = array_chunk($events, 6);
        return json_encode($events);
    }

    public function getCinemas() {

        $cinemas = SiteTenantViewModel::where('site_tenants.active', 1)
        ->where('brands.name', 'like', '%CINEMA%')
        ->where('brands.name', 'not like', '%LOBBY%')
        ->where('categories.name', 'like', '%Amusement & Exhibitions%')
        ->where('site_tenants.site_id', $this->site->id)
        ->join('brands', 'site_tenants.brand_id', '=', 'brands.id')
        ->join('categories', 'brands.category_id', '=', 'categories.id')
        ->select('site_tenants.*')
        ->distinct()
        ->orderBy('brands.name', 'ASC')
        ->get()->toArray();
        
        $cinemas = array_chunk($cinemas, 12);
        return json_encode($cinemas);

    }

    public function getShowing() {

        $start_date =  date('Y-m-d 00:00:00');
        $end_date =  date('Y-m-d 23:59:59');
        
        // $now_showing = CinemaScheduleViewModel::where('site_id', $this->site->id)
        // ->select('site_id', 'film_id', 'title', 'rating', 'casting', 'screen_name', 'trailer_url', 'genre', 'synopsis')
        // ->groupBy('film_id')
        // ->orderBy('title')
        // ->get()->toArray();

        $now_showing = CinemaScheduleViewModel::where('show_time', '>=', $start_date)
        ->where('show_time', '<=', $end_date)
        ->where('site_id', $this->site->id)
        ->select('site_id', 'film_id', 'title', 'rating', 'casting', 'screen_name', 'trailer_url', 'genre', 'synopsis')
        ->groupBy('film_id')
        ->orderBy('title')
        ->get()->toArray();
        
        $now_showing = array_chunk($now_showing, 3);
        return json_encode($now_showing);

    }

    public function getSuggestionList() {

        $tenants = SiteTenantViewModel::where('site_tenants.site_id', $this->site->id)
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
        $brand_names = [];
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
                    'tenant' => $value,
                ]);

                $brand_names[] = ucfirst(strtolower($value->brand_name));
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
            ->whereNotIn('name',  $brand_names)
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

        $site_ids = [0, $this->site->id];
        $amenities = Amenity::where('active', 1)
            ->whereIn('site_id',  $site_ids)
            ->orderBy('name', 'ASC')
            ->get()->pluck('name');

        foreach ($amenities as $key => $value) {
            $collection->push([
                'id' => null,
                'value' => addslashes($value),
                'floor_name' => null,
                'building_name' => null,
                'orderby' => addslashes($value),
            ]);
        }

        return json_encode($collection->sortBy('value')->values()->all());
    }

    public function search(Request $request) {
        try
        {
            $site = SiteViewModel::find($request->site_id);
            $site_map_ids = SiteMap::where('site_id', $request->site_id)
            ->where('map_type', $site->details['map_type'])
            ->get()
            ->pluck('id');

            $keyword = preg_replace('!\s+!', ' ', $request->key_words);   

            $tenants = SitePointTenantViewModel::whereIn('site_map_id', $site_map_ids)
            ->where(function ($query) {
                $query->where('site_points.tenant_id', '>', 0)
                        ->orWhere('site_points.point_type', '>', 0);
            })
            ->where(function ($query) use($keyword) {
                $query->orWhere('brands.name', 'like', '%'.$keyword.'%')
                ->orWhere('brands.name', 'like', $keyword.'%') #LAST WORD but start on first letter | #BETWEEN WORDS
                ->orWhere('brands.name', 'like', '%'.$keyword) #FIRST WORD but start on first letter
                ->orWhere('categories.name', 'like', '%'.$keyword)
                ->orWhere('categories.name', 'like', $keyword.'%')
                ->orWhere('supp.name', 'like', $keyword.'%')
                ->orWhere('supp.name', 'like', '%'.$keyword)
                ->orWhere('tags.name', 'like', $keyword.'%')
                ->orWhere('tags.name', 'like', '%'.$keyword)
                ->orWhere('amenities.name', 'like', $keyword.'%')
                ->orWhere('amenities.name', 'like', '%'.$keyword);
            })
            ->leftJoin('site_tenants', 'site_points.tenant_id', '=', 'site_tenants.id')
            ->leftJoin('site_tenant_metas', function($join) {
                $join->on('site_tenants.id', '=', 'site_tenant_metas.site_tenant_id')
                ->where('site_tenant_metas.meta_key', 'address');
            })
            ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
            ->leftJoin('brand_supplementals', 'site_tenants.brand_id', '=', 'brand_supplementals.brand_id')
            ->leftJoin('categories as supp', 'brand_supplementals.supplemental_id', '=', 'supp.id')
            ->leftJoin('brand_tags', 'brands.id', '=', 'brand_tags.brand_id')
            ->leftJoin('tags', 'brand_tags.tag_id', '=', 'tags.id')
            ->leftJoin('amenities', 'site_points.point_type', '=', 'amenities.id')
            ->leftJoin('site_maps', 'site_points.site_map_id', '=', 'site_maps.id')
            ->leftJoin('site_building_levels', 'site_maps.site_building_level_id', '=', 'site_building_levels.id')
            ->select('site_tenants.*', 'brands.category_id as brand_category_id', 'site_tenant_metas.meta_value as address', 
            'site_points.id as site_point', 'amenities.name as amenity_name', 'amenities.icon', 'site_building_levels.name as amenity_location')
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
            ->where('site_tenants.active',  1)
            ->whereIn('brands.category_id',  $suggest_cat)
            ->join('site_tenant_metas', 'site_tenants.id', '=', 'site_tenant_metas.site_tenant_id')
            ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->select('site_tenants.*')
            ->distinct()
            ->get()->toArray();

            $tenants = array_chunk($tenants->toArray(), 12);                

            return [
                'tenants' => $tenants,
                'suggest_subscribers' => $suggest_subscribers,
            ];
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        } 
    }

    public function getBannerAds() {

        $site_screen = SiteScreen::where('is_default', 1)->where('active', 1)->where('site_id', $this->site->id)->first();
        if(!$site_screen)
            return null;
        
        $site_screen_id = $site_screen->id;
        $current_date = date('Y-m-d');

        $playlist = PlayListViewModel::where('play_lists.site_screen_id', $site_screen_id)
            ->where('content_management.status_id', 5)
            ->where('content_management.active', 1)
            ->where('site_screen_products.ad_type', 'Banner Ad')
            ->whereNull('content_management.deleted_at')
            ->whereDate('content_management.start_date', '<=', $current_date)
            ->whereDate('content_management.end_date', '>=', $current_date)
            ->join('content_management', 'play_lists.content_id', '=', 'content_management.id')
            ->leftJoin('site_screen_products', function($join)
            {
                $join->on('play_lists.site_screen_id', '=', 'site_screen_products.site_screen_id')
                     ->whereRaw('play_lists.dimension = site_screen_products.dimension');
            })            
            ->select('play_lists.*')
            ->orderBy('play_lists.sequence', 'ASC')
            ->get()
            ->toArray();

        return json_encode($playlist);
    }

    public function getFullScreenAds() {

        $site_screen = SiteScreen::where('is_default', 1)->where('active', 1)->where('site_id', $this->site->id)->first();
        if(!$site_screen)
            return null;

        $site_screen_id = $site_screen->id;
        $current_date = date('Y-m-d');

        $playlist = PlayListViewModel::where('play_lists.site_screen_id', $site_screen_id)
        ->where('content_management.status_id', 5)
        ->where('content_management.active', 1)
        ->where('site_screen_products.ad_type', 'Full Screen Ad')
        ->whereNull('content_management.deleted_at')
        ->whereDate('content_management.start_date', '<=', $current_date)
        ->whereDate('content_management.end_date', '>=', $current_date)
        ->join('content_management', 'play_lists.content_id', '=', 'content_management.id')
        ->leftJoin('site_screen_products', function($join)
        {
            $join->on('play_lists.site_screen_id', '=', 'site_screen_products.site_screen_id')
                    ->whereRaw('play_lists.dimension = site_screen_products.dimension');
        })            
        ->select('play_lists.*')
        ->select('play_lists.*')
        ->orderBy('play_lists.sequence', 'ASC')
        ->get()
        ->toArray();

        return json_encode($playlist);
    }

    public function putLikeCount(Request $request)
    {
        $site_tenant = SiteTenant::find($request->id);
        $site_tenant->like_count = ($request->action == 'minus') ? $site_tenant->like_count-1 : $site_tenant->like_count+1;
        $site_tenant->save();

        return $site_tenant;
    }

    public function putViewCount(Request $request)
    {
        $site_tenant = SiteTenant::find($request->id);
        $site_tenant->view_count = $site_tenant->view_count+1;
        $site_tenant->save();

        return $site_tenant;
    }

    public function getTenantCountDetails(Request $request)
    {
        return SiteTenant::find($request->id);
    }

    public function getAssistantMessage()
    {
        $messages = AssistantMessageViewModel::all();

        $collection = collect([]);
        foreach ($messages as $value) {
            $content = filter_var(htmlentities(preg_replace("/\r\n|\r|\n|\t/", ' ', $value->content)), FILTER_SANITIZE_STRING);

            $collection->push([
                'location' => $value->location,
                'content' => $content,
                'content_language' => $value->content_language,
            ]);
        }

        return json_encode($collection);
    }

    public function getTranslation()
    {
        $translations = TranslationViewModel::all();

        $collection = collect([]);
        foreach ($translations as $value) {
            $translated = filter_var(htmlentities(preg_replace("/\r\n|\r|\n|\t/", ' ', $value->translated)), FILTER_SANITIZE_STRING);
            $english = filter_var(htmlentities(preg_replace("/\r\n|\r|\n|\t/", ' ', $value->english)), FILTER_SANITIZE_STRING);
            $collection->push([
                'language' => $value->language,
                'english' => $english,
                'translated' => $translated,
            ]);
        }

        return json_encode($collection);
    }

    public function getSiteMaps() {
        $site_maps = SiteMapViewModel::where('site_id', $this->site->id)
        ->where('map_type', $this->site->details['map_type'])
        ->where('active', 1)
        ->get();
        return $site_maps;        
    }

    public function getSiteConfig() {
        $config = SiteMapConfigViewModel::where('site_maps.site_id', $this->site->id)
        ->where('site_maps.map_type', $this->site->details['map_type'])
        ->where('site_map_configs.is_default', 1)
        ->where('site_map_configs.active', 1)
        ->join('site_maps', 'site_map_configs.site_map_id', '=', 'site_maps.id')
        ->select('site_map_configs.*')
        ->first();

        return $config;
    }

    public function getMapPointsTenantLinks($site_maps, $tenant_list) {
        $map_ids = $site_maps->pluck('id');
       
        $points_tmp = SitePointViewModel::whereIn('site_map_id', $map_ids)
        ->leftJoin('site_maps', 'site_points.site_map_id', '=', 'site_maps.id')
        ->select('site_points.*', 'site_maps.site_building_level_id as building_level_id')
        ->get();

        $points = [];
        $tenants = [];
        $tenant_points = [];

        foreach($points_tmp as $point) {
            $points[$point['id']] = $point;
            $tenant_points[] = $point['tenant_id'];
        }

        foreach($tenant_list as $tenant) {
            if(in_array($tenant['id'],$tenant_points)) {
                $tenants[$tenant['id']] = $tenant;
            }
        }

        return [
            'map_points' => $points,
            'map_tenants' => $tenants,
        ];
    }

    public function getAmenities() {
        $amenities = [];

        // GET AMENITIES WITH SITE ID
        $site_amenities = Amenity::where('site_id', $this->site->id)->where('active', 1)->get();
        if($site_amenities) {
            foreach($site_amenities as $amenity) {
                if($amenity->icon != null || $amenity->icon != '')
                    $amenities[$amenity->id] = $amenity->icon; 
            }
        }

        // GET DEFAULT AMENITIES
        $default_amenities = Amenity::where('site_id', 0)->where('active', 1)->get();
        if($default_amenities) {
            foreach($default_amenities as $amenity) {
                if($amenity->icon != null || $amenity->icon != '')
                    $amenities[$amenity->id] = $amenity->icon; 
            }
        }

        return json_encode($amenities);
    }

    public function getBuildingFloors() {

        $building = SiteBuilding::where('site_id', $this->site->id)
        ->where('active', 1)
        ->get()->count();

        $floors = SiteBuildingLevelViewModel::where('site_id', $this->site->id)
        ->where('active', 1)->get();

        return [
            'building' => $building,
            'floors' => $floors
        ];
    }

    public function getMapZoom($site_maps, $view_angle) {
        $map_ids = $site_maps->pluck('id');
        $kiosk_zoom = [];

        $map_zoom = SiteMapZoom::whereIn('site_map_id', $map_ids)
        ->where('view_angle', $view_angle)
        ->whereIn('meta_key', ['zoomratio','movetop','fitscreen'])
        ->leftJoin('site_maps', 'site_map_zooms.site_map_id', '=', 'site_maps.id')
        ->select('site_map_zooms.*', 'site_maps.site_building_level_id as building_level_id')
        ->get();

        foreach($map_zoom as $zoom) {
            $kiosk_zoom[$zoom->building_level_id][$zoom->meta_key] = $zoom->meta_value;
        }

        return json_encode($kiosk_zoom);
    }

    public function getMapCenter($site_maps, $view_angle, $origin_point = null) {
        $map_ids = $site_maps->pluck('id');
        $kiosk_zoom = [];

        $map_zoom = SiteMapZoom::whereIn('site_map_id', $map_ids)
        ->where('view_angle', $view_angle)
        ->whereIn('meta_key', ['center_x','center_y','center_z'])
        ->when($origin_point, function($query) use($origin_point) {
            $query->where('origin_point', $origin_point);
        })
        ->leftJoin('site_maps', 'site_map_zooms.site_map_id', '=', 'site_maps.id')
        ->select('site_map_zooms.*', 'site_maps.site_building_level_id as building_level_id')
        ->get();

        foreach($map_zoom as $zoom) {
            $kiosk_zoom[$zoom->building_level_id][$zoom->meta_key] = $zoom->meta_value;
        }

        return $kiosk_zoom;
    }
    
    public function getPath(Request $request) {
        $from = $request->from;
        $to = $request->to;
        $pwd = $request->pwd;
        $type = $request->type;
        $this->site = SiteViewModel::find($request->site_id);

        // GET MAPS PER SITE
        $site_maps = $this->getSiteMaps();
        $maps_ids = [];

        foreach($site_maps as $map) {
            $maps_ids[] = $map->id;
        }

        // GET SITE BUILDINGS AND FLOORS
        $site_buildings = $this->getBuildingFloors();

        $maps_filter_ids = [];
		$maps_final_ids = [];

        if($type == 'store') {
            $store_point_a = SitePointViewModel::whereIn('site_map_id', $maps_ids)
            ->where('site_points.tenant_id', $from)
            ->leftJoin('site_maps', 'site_points.site_map_id', '=', 'site_maps.id')
            ->select('site_points.*', 'site_maps.site_building_id as building_id', 'site_maps.site_building_level_id as building_level_id')
            ->get()->toArray();
        }
        else{
            $store_point_a = SitePointViewModel::where('site_points.id', $from)
            ->leftJoin('site_maps', 'site_points.site_map_id', '=', 'site_maps.id')
            ->select('site_points.*', 'site_maps.site_building_id as building_id', 'site_maps.site_building_level_id as building_level_id')
            ->get()->toArray();
        }
        
        $store_point_b = SitePointViewModel::whereIn('site_map_id', $maps_ids)
        ->where('tenant_id', $to)
        ->leftJoin('site_maps', 'site_points.site_map_id', '=', 'site_maps.id')
        ->select('site_points.*', 'site_maps.site_building_id as building_id', 'site_maps.site_building_level_id as building_level_id')
        ->get()->toArray();

         // if same building and floor
        if($store_point_a[0]['site_map_id'] == $store_point_b[0]['site_map_id']) {
            $maps_filter_ids[] = $store_point_b[0]['site_map_id'];
            $maps_final_ids = array_intersect($maps_ids, $maps_filter_ids);
        }
        else {
            // CHECK IF MULTIPLE BUILDING
            if($site_buildings['building'] > 1) {
                // MULTI BUILDING PROCESS HERE
            }
            else {
                $maps_filter_ids[] = $store_point_a[0]['site_map_id'];
                $maps_filter_ids[] = $store_point_b[0]['site_map_id'];
                $maps_final_ids = array_intersect($maps_ids, $maps_filter_ids);    
            }
        }

        // GET MAP POINTS
        $points_tmp = SitePointViewModel::whereIn('site_map_id', $maps_final_ids)
        ->leftJoin('site_maps', 'site_points.site_map_id', '=', 'site_maps.id')
        ->leftJoin('site_building_levels', 'site_maps.site_building_level_id', '=', 'site_building_levels.id')
        ->select('site_points.*', 'site_maps.site_building_level_id as building_level_id', 'site_maps.site_building_id as building_id',
        'site_building_levels.name as level_name')
        ->get();

        $points = [];
        foreach($points_tmp as $point) {
            if($pwd) {
                if($point['is_pwd'])
				{
					if($point['tenant_id'] == $to){
						$points[$point['id']] = $point;
					}
                    elseif($point['tenant_id'] == 0){
						$points[$point['id']] = $point;
					}
				}
            }
            else {
                if($point['point_type'] != 2)
                {
                    if($point['tenant_id'] == $to) {
                        $points[$point['id']] = $point;
                    }
                    elseif($point['tenant_id'] == 0){
                        $points[$point['id']] = $point;
                    }
                }
            }
        }
   
        // GET MAP POINT LINKS
        $point_links_tmp = SitePointLinkViewModel::whereIn('site_map_id', $maps_final_ids)->get()->toArray();
		$point_links = [];
        
        foreach($point_links_tmp as $link) {
            if($link['point_a'] && $link['point_b'] && array_key_exists($link['point_a'],$points) && array_key_exists($link['point_b'],$points)) {
                $distance = $this->getDistance($points[$link['point_a']]['point_x'], 
                            $points[$link['point_a']]['point_z'],
                            $points[$link['point_b']]['point_x'],
                            $points[$link['point_b']]['point_z']);
                $point_a = intval($link['point_a']);
                $point_b = intval($link['point_b']);
                
                if(!isset($point_links[$point_a])) $point_links[$point_a] = [];
                if(!isset($point_links[$point_a][$point_b]))
                {
                    $point_links[$point_a][$point_b] = abs($distance);
                }
                
                if(!isset($point_links[$point_b])) $point_links[$point_b] = [];
                if(!isset($point_links[$point_b][$point_a]))
                {
                    $point_links[$point_b][$point_a] = abs($distance);
                }
            }

        }

        // get tenant name and building name and floor name
		$tenant_guide = SiteTenantViewModel::find($to);
        $building_guide = $tenant_guide['building_name'];
        $level_guide = $tenant_guide['floor_name'];

        if($building_guide == "Main Building"){
			$building_guide = '';
		}

        $shortestpath = $this->findShortestPath($point_links,$store_point_a[0]['id'],$store_point_b[0]['id']);
        $path = $shortestpath['path'];
		$coordinates = [];
        $guide = [];

		$current_level = 0;
		$total_levels = 0;
		$level_end_points = [];
		$adjustment = ($type == 'store') ? 2 : 1;

        for($i=count($path)-$adjustment;$i>0;$i--)
		{
			if(!isset($level_end_points[$points[$path[$i]]['building_level_id']])) $level_end_points[$points[$path[$i]]['building_level_id']] = [];
			if($current_level == 0)
			{
				$level_end_points[$points[$path[$i]]['building_level_id']][] = $points[$path[$i]];
				$total_levels++;
			}
            elseif($current_level <> $points[$path[$i]]['building_level_id']){
				if(!isset($level_end_points[$current_level])) $level_end_points[$current_level] = [];
				$level_end_points[$current_level][] = $points[$path[$i+1]];
				$level_end_points[$points[$path[$i-1]]['building_level_id']][] = $points[$path[$i-1]];
				$total_levels++;
			}

			$coordinates[] = ($points[$path[$i]] ?? 0);
			$current_level = $points[$path[$i]]['building_level_id'];
		}
		$level_end_points[$points[$path[$i]]['building_level_id']][] = $points[$path[$i]];

        $directions = $this->generateDirections($coordinates);
        $directions[] = 'Follow the <span class="text-danger">red path</span> to your destination';

        // if multi building exist
        if($building_guide != ''){
            $textdestination = $tenant_guide->brand_name.', '.$building_guide.' - '.$level_guide;
        }else{
            $textdestination = $tenant_guide->brand_name.', '.$level_guide;
        }

        return ['coords'=>$coordinates,
                'distance'=>$shortestpath['distance'],
                'guide'=>$directions,
                'level_end_points'=>$level_end_points,
                'directions'=>$directions,
                'total_levels'=>$total_levels,
                'destination'=>$store_point_b,
                'tenant_guide'=>$textdestination
        ];
    }

    private function getDistance($x1,$y1,$x2,$y2) {
		$pi = pi(); 
		$x = sin($x1 * $pi/180) * 	
			sin($x2 * $pi/180) + 
			cos($x1 * $pi/180) * 
			cos($x2 * $pi/180) * 
			cos(($y2 * $pi/180) - ($y1 * $pi/180));
		$x = atan((sqrt(1 - pow($x, 2))) / $x); 
		return ($x / $pi) * 180;
	}

    private function shortestDistanceNode($distances, $visited) {
		$shortest = null;
		foreach (array_keys($distances) as $node) {
			$currentIsShortest = $shortest === null || $distances[$node] < $distances[$shortest];

			if ($currentIsShortest && !in_array($node,$visited)) {
				$shortest = $node;
			}
		}
		return $shortest;
	}

	private function findShortestPath($graph,$startNode, $endNode) {
		$distances = [];
		$distances[$endNode] = INF;
		foreach($graph[$startNode] as $node=>$distance)
		{
			$distances[$node] = $distance;
		}

        // track paths
		$parents = [ $endNode => null ];
		foreach (array_keys($graph[$startNode]) as $child) {
			$parents[$child] = $startNode;
		}

		// track nodes that have already been visited
		$visited = [];


		// find the nearest node
		$node = $this->shortestDistanceNode($distances, $visited);
		while ($node) {
			$distance = $distances[$node];
			$children = $graph[$node];

			foreach(array_keys($children) as $child)
			{
				if($child == $startNode)
				{
					continue;
				}else{
					$newdistance = $distance + $children[$child];

					if (!isset($distances[$child]) || $distances[$child] > $newdistance) 
					{
						$distances[$child] = $newdistance;
						$parents[$child] = $node;
					}
				}
			}
			// move the node to the visited set
			$visited[] = $node;	
			$node = $this->shortestDistanceNode($distances, $visited);
		}

		// using the stored paths from start node to end node
		// record the shortest path
		$shortestPath = [$endNode];
		$parent = $parents[$endNode];
		while ($parent) {
			$shortestPath[] = $parent;
			$parent = $parents[$parent] ?? 0;
		}

        return [
			'distance' => $distances[$endNode],
			'path' => $shortestPath
		];
	}

    private function generateDirections($SamplePath) {
        $TURNTHRESHOLD = deg2rad(10);

		$PathList = [];

		//create temporary Pathlist
		// note: comment this one if used in Podium
		$temp_pathlist_level = [];
		
		$LoopMax = count($SamplePath) - 3;
		$LoopCount = 0;
		$current_turn = '';
		while ($LoopCount <= $LoopMax)
		{
			$ThreePoints = [
			0 => $SamplePath[$LoopCount],
			1 => $SamplePath[$LoopCount+1],
			2 => $SamplePath[$LoopCount+2],
			];

			$TranslatedPoints = $this->translatePoints($ThreePoints);
			$RotationAngle = $this->computeAngle($TranslatedPoints[1]);
			$NewSecondPoint = $this->rotatePoint($TranslatedPoints[1], $RotationAngle * ($TranslatedPoints[1]['point_x'] / abs($TranslatedPoints[1]['point_x'] == 0 ? 1 : $TranslatedPoints[1]['point_x'])));
			$NewThirdPoint = $this->rotatePoint($TranslatedPoints[2], $RotationAngle * ($TranslatedPoints[2]['point_x'] / abs($TranslatedPoints[2]['point_x'] == 0 ? 1 : $TranslatedPoints[2]['point_x'])));
			$ThirdPointAngle = $this->computeAngle($NewThirdPoint);
			
			if (!((abs($ThirdPointAngle) >= $TURNTHRESHOLD) && (abs($ThirdPointAngle) < 180))) {
				//$PathList[] = "STRAIGHT";
			}
            elseif ($NewThirdPoint['point_x'] < 0) {
                $current_turn = 'Turn Right';
                $PathList[] = "{$current_turn}"  . ($SamplePath[$LoopCount+1]['point_label'] ? " on {$SamplePath[$LoopCount+1]['point_label']}" : "");
			}else{
                $current_turn = 'Turn Left';
                $PathList[] = "{$current_turn}"  . ($SamplePath[$LoopCount+1]['point_label'] ? " on {$SamplePath[$LoopCount+1]['point_label']}" : "");
            }				
		
			if($ThreePoints[1]['building_id'] != $ThreePoints[2]['building_id'])
			{
				$PathList[] = "Take {$ThreePoints[2]['point_label']} to " . $ThreePoints[2]['building_name'];

			}else{
				if($ThreePoints[1]['building_level_id'] != $ThreePoints[2]['building_level_id'])
				{
					$PathList[] = "Take {$ThreePoints[2]['point_label']} to " . $ThreePoints[2]['level_name'];
					// note: comment this one if used in Podium
					$temp_pathlist_level[] = "Take {$ThreePoints[2]['point_label']} to " . $ThreePoints[2]['level_name'];
				}
			}

			$LoopCount++;
		}
		
		return $PathList;
	}

    private function translatePoints($ThreePoints) {
		$ReturnValue = [
			'success' => 0,
			0 => [
				'point_x' => 0,
				'point_y' => $ThreePoints[0]['point_y'],
				'point_z' => 0,
			],
			1 => [
				'point_x' => $ThreePoints[1]['point_x'] - $ThreePoints[0]['point_x'],
				'point_y' => $ThreePoints[1]['point_y'],
				'point_z' => $ThreePoints[1]['point_z'] - $ThreePoints[0]['point_z'],
			],
			2 => [
				'point_x' => $ThreePoints[2]['point_x'] - $ThreePoints[0]['point_x'],
				'point_y' => $ThreePoints[2]['point_y'],
				'point_z' => $ThreePoints[2]['point_z'] - $ThreePoints[0]['point_z'],
			],
		];
		return $ReturnValue;
	}

    private function computeAngle($TargetPoint) {
		$Radius = sqrt(pow($TargetPoint['point_x'], 2) + pow($TargetPoint['point_z'], 2));
		$OppLineWidth = sqrt(pow($TargetPoint['point_x'], 2) + pow($TargetPoint['point_z'] - $Radius, 2));
		$Angle = acos((2 * pow($Radius, 2) - pow($OppLineWidth, 2)) / (2 * pow($Radius, 2)));

		return $Angle;
	}

    private function rotatePoint($TargetPoint, $Radians) {
		$ReturnValue = [
			'point_x' => round(($TargetPoint['point_x'] * cos($Radians)) - ($TargetPoint['point_z'] * sin($Radians)), 10),
			'point_y' => $TargetPoint['point_y'],
			'point_z' => round($TargetPoint['point_x'] * sin($Radians) + $TargetPoint['point_z'] * cos($Radians), 10),
		];
	
		return $ReturnValue;
	}
    
}
