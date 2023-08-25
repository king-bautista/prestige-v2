<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ViewModels\SiteViewModel;
use App\Models\ViewModels\CategoryViewModel;
use App\Models\ViewModels\SiteTenantViewModel;
use App\Models\ViewModels\ContentManagementViewModel;
use App\Models\ViewModels\BrandProductViewModel;
use App\Models\ViewModels\CinemaScheduleViewModel;
use App\Models\ViewModels\SiteBuildingLevelViewModel;
use App\Models\ViewModels\SiteMapViewModel;
use App\Models\ViewModels\SitePointViewModel;
use App\Models\ViewModels\SiteScreenViewModel;
use App\Models\ViewModels\DirectoryCategoryViewModel;
use App\Models\ViewModels\DirectorySiteTenantViewModel;
use App\Models\ViewModels\AssistantMessageViewModel;
use App\Models\ViewModels\TranslationViewModel;
use App\Models\ViewModels\PlayListViewModel;
use App\Models\Site;
use App\Models\SitePoint;
use App\Models\SiteMapPaths;
use App\Models\SiteBuilding;
use App\Models\SiteScreen;
use App\Models\SiteFeedback;
use App\Models\Landmark;
use App\Models\Event;

class MainController extends AppBaseController
{
    public function index()
    {
        $site = Site::where('is_default', 1)->where('active', 1)->first();
        $site_screen = SiteScreen::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first();            
        $site['site_screen_id'] = $site_screen->id;
        $site['site_orientation'] = $site_screen->orientation;
        $site['site_website'] = '';
        $site_name = $site->name;
        
        $siteMeta = SiteViewModel::find($site->id);

        if ($siteMeta->details['premiere']) {
            $arr = explode(" ",$site_name);
            $site_name = strtolower(implode("-",$arr));
            $site['site_name'] = $site_name;
        } else {
            $site_name = 'generic';
            $site['site_name'] = $site_name;
        }

        if($siteMeta->details['website'])
            $site['site_website'] = $siteMeta->details['website'];

        return view('kiosk.main', $site);
    }

    public function getSite()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_screen = SiteScreen::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first();            
            $site['site_orientation'] = $site_screen->orientation;

            return $this->response($site, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Site to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getCategories()
    {
        // try
        // {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $categories = DirectoryCategoryViewModel::getMainCategory($site->id)->orderBy('sequence')->get();            
            return $this->response($categories, 'Successfully Retreived!', 200);
        // }
        // catch (\Exception $e)
        // {
        //     return response([
        //         'message' => 'No Categories to display!',
        //         'status_code' => 200,
        //     ], 200);
        // }
    }

    // public function getTenantsAlphabetical($category_id)
    // {
    //     try
    //     {
    //         $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
    //         $site_tenants = SiteTenantViewModel::where('site_tenants.active', 1)
    //         ->where('categories.parent_id', $category_id)
    //         ->where('site_tenants.site_id', $site->id)
    //         ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
    //         ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
    //         ->select('site_tenants.*')
    //         ->orderBy('brands.name', 'ASC')
    //         ->get()->toArray();
            
    //         $site_tenants = array_chunk($site_tenants, 15);
    //         return $this->response($site_tenants, 'Successfully Retreived!', 200);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return response([
    //             'message' => 'No Tenants to display!',
    //             'status_code' => 200,
    //         ], 200);
    //     }    
    // }

    public function getTenantsByCategory($category_id)
    {
        try
        {
            if (config('app.env') == 'local') {
                $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
                $site_tenants = DirectorySiteTenantViewModel::where('site_tenants.active', 1)
                ->where('brands.category_id', $category_id)
                ->where('site_tenants.site_id', $site->id)
                ->join('brands', 'site_tenants.brand_id', '=', 'brands.id')
                ->select('site_tenants.*')
                ->orderBy('brands.name', 'ASC')
                ->get()->toArray();
            } else {
                $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
                $site_tenants = DirectorySiteTenantViewModel::where('site_tenants.active', 1)
                ->where('brands.category_id', $category_id)
                ->where('site_tenants.site_id', $site->id)
                ->join('brands', 'site_tenants.brand_id', '=', 'brands.id')
                ->join('site_points', 'site_tenants.id', '=', 'site_points.tenant_id')
                ->select('site_tenants.*')
                ->orderBy('brands.name', 'ASC')
                ->get()->toArray();
            }

            $site_screen = SiteScreen::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first();            
            
            $per_set = 12;
            if($site_screen->orientation == 'Portrait') {
                $per_set = 15;
            } 

            $site_tenants = array_chunk($site_tenants, $per_set);
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }    
    }

    public function getTenantsBySupplementals($category_id)
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_tenants = DirectorySiteTenantViewModel::where('site_tenants.active', 1)
            ->where('brand_supplementals.supplemental_id', $category_id)
            ->where('site_tenants.site_id', $site->id)
            ->join('brand_supplementals', 'site_tenants.brand_id', '=', 'brand_supplementals.brand_id')
            ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->select('site_tenants.*')
            ->orderBy('brands.name', 'ASC')
            ->get()->toArray();
            
            $site_tenants = array_chunk($site_tenants, 15);
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }    
    }

    public function getSuggestionList()
    {
        try
        {
            // # GET ALL TENANT BRAND
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_screen_id = SiteScreen::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first()->id;
            $site_tenants = SiteTenantViewModel::where('site_tenants.active', 1)
            ->where('site_tenants.site_id', $site->id)
            ->where('site_maps.site_screen_id', $site_screen_id)
            ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
            ->join('site_points', 'site_tenants.id', '=', 'site_points.tenant_id')
            ->join('site_maps', 'site_points.site_map_id', '=', 'site_maps.id')
            ->select('site_tenants.id','site_tenants.brand_id','brands.name','site_tenants.site_building_id','site_tenants.site_building_level_id')
            ->orderBy('brands.name', 'ASC')
            ->get();
            // ->pluck('name','id','site_building_id','site_building_level_id');

            $duplicated_brand_ids = DB::table('site_tenants')
                ->where('site_tenants.site_id', $site->id)
                ->where('site_tenants.active', 1)
                ->select('site_tenants.brand_id')
                ->groupBy('site_tenants.brand_id')
                ->havingRaw('count(*) > 1')
                ->get()
                ->pluck('brand_id');

                // dd($duplicated_brand_ids);

            $collection = collect([]);

            foreach ($site_tenants as $key => $value) {

                if (in_array($value->brand_id,$duplicated_brand_ids->toArray())) {
                    $collection->push([
                        'id' => $value->id,
                        'value' => $value->name, // . ", " . $value->floor_name . ", " . $value->building_name,
                        'floor_name' => $value->floor_name,
                        'building_name' => $value->building_name
                    ]);
                } else {
                    $collection->push([
                        'id' => $value->id,
                        'value' => $value->name,
                        'floor_name' => null,
                        'building_name' => null
                    ]);
                }  
                
            }

            // dd($collection);

            # GET ALL TENANT TAGS
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_tenants = DB::table('site_tenants')
                ->where('site_tenants.site_id', $site->id)
                ->where('site_tenants.active', 1)
                ->join('site_points', 'site_tenants.id', '=', 'site_points.tenant_id')
                ->select('site_tenants.brand_id')
                ->get()
                ->pluck('brand_id');
            $brand_tags = DB::table('brand_tags')
                ->whereIn('brand_tags.brand_id', $site_tenants)
                ->select('brand_tags.tag_id')
                ->get()
                ->pluck('tag_id');
            $tags = DB::table('tags')
                ->whereIn('tags.id',  $brand_tags)
                ->select('tags.name')
                ->get()
                ->pluck('name');

            foreach ($tags as $key => $value) {
                $collection->push([
                    'id' => null,
                    'value' => $value,
                    'floor_name' => null,
                    'building_name' => null
                ]);
            }

            $collection->all();

            return $this->response($collection, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }  
    }

    public function getAllTenants()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_tenants = DirectorySiteTenantViewModel::where('site_tenants.active', 1)
            ->where('site_tenants.site_id', $site->id)
            ->join('site_points', 'site_tenants.id', '=', 'site_points.tenant_id')
            ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
            ->select('brands.name', 'site_tenants.*')
            ->orderBy('brands.name', 'ASC')
            ->groupBy('brands.name')
            ->get();
            
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }  
    }

    public function search(Request $request)
    {
        try
        {
            //FIND BY KEYWORD and display as list E.G TAGS/KEY_WORDDS/BRAND_NAME
            if (!$request->id) {
                $array_words = explode(' ', $request->key_words);
                $keyword = preg_replace('!\s+!', ' ', $request->key_words);                

                $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
                $site_tenants = DirectorySiteTenantViewModel::where('site_tenants.active', 1)
                // ->where(function ($query) use($array_words) {
                ->where(function ($query) use($keyword) {
                    // foreach($array_words as $key) {
                        //$query->orWhere('brands.name', 'like', '%'.$keyword.'%')
                        $query->orWhere('brands.name', 'like', $keyword.'%') #LAST WORD but start on first letter | #BETWEEN WORDS
                        ->orWhere('brands.name', 'like', '%'.$keyword) #FIRST WORD but start on first letter
                        ->orWhere('categories.name', 'like', '%'.$keyword)
                        ->orWhere('categories.name', 'like', $keyword.'%')
                        ->orWhere('supp.name', 'like', $keyword.'%')
                        ->orWhere('supp.name', 'like', '%'.$keyword)
                        ->orWhere('tags.name', 'like', $keyword.'%')
                        ->orWhere('tags.name', 'like', '%'.$keyword);
                    // }
                })
                ->where('site_tenants.site_id', $site->id)
                ->join('brands', 'site_tenants.brand_id', '=', 'brands.id')
                ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
                ->leftJoin('brand_supplementals', 'site_tenants.brand_id', '=', 'brand_supplementals.brand_id')
                ->leftJoin('categories as supp', 'brand_supplementals.supplemental_id', '=', 'supp.id')
                ->leftJoin('brand_tags', 'brands.id', '=', 'brand_tags.brand_id')
                ->leftJoin('tags', 'brand_tags.tag_id', '=', 'tags.id')
                ->join('site_points', 'site_tenants.id', '=', 'site_points.tenant_id')
                ->select('site_tenants.*')
                ->distinct()
                ->orderBy('brands.name', 'ASC')
                ->get();

                $suggest_cat = [];

                foreach ($site_tenants as $key => $value) {
                    array_push( $suggest_cat, $value->category_id);           
                }

                $suggest_cat =(array_unique($suggest_cat));

                $suggest_subscribers = DirectorySiteTenantViewModel::where('site_tenants.is_subscriber',  1)
                ->join('site_tenant_metas', 'site_tenants.id', '=', 'site_tenant_metas.site_tenant_id')
                ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
                ->whereIn('brands.category_id',  $suggest_cat)
                ->select('site_tenants.*')
                ->distinct()
                ->get();
                
                $counts = $site_tenants->count();
                $suggest_subscribers_counts = $suggest_subscribers->count();
                
                if ($suggest_subscribers_counts > 0) {
                    $site_tenants = array_chunk($site_tenants->toArray(), 9);
                } else {
                    $site_tenants = array_chunk($site_tenants->toArray(), 12);
                }

                // dd($site_tenants);

                return $this->response([$site_tenants,$suggest_subscribers], 'Successfully Retreived!', 200, $counts);
            //FIND BY ID and display as Tenant Page
            } else {
                $site_tenants = DirectorySiteTenantViewModel::where('site_tenants.active', 1)
                ->where('site_tenants.id', $request->id)
                ->join('brands', 'site_tenants.brand_id', '=', 'brands.id')
                ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
                ->leftJoin('brand_supplementals', 'site_tenants.brand_id', '=', 'brand_supplementals.brand_id')
                ->leftJoin('categories as supp', 'brand_supplementals.supplemental_id', '=', 'supp.id')
                ->leftJoin('brand_tags', 'brands.id', '=', 'brand_tags.brand_id')
                ->leftJoin('tags', 'brand_tags.tag_id', '=', 'tags.id')
                ->select('site_tenants.*')
                ->distinct()
                ->orderBy('brands.name', 'ASC')
                ->get();
                
                $counts = $site_tenants->count();
                // $site_tenants = array_chunk($site_tenants->toArray(), 12);

                return $this->response($site_tenants, 'Successfully Retreived!', 200, $counts);
            }
            
            
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }    
    }

    public function listToArray($list)
    {
        $arrays = [];
        if($list) {
            foreach($list as $index => $data) {
                $arrays[] = $data;
            }
        }
        return $arrays;
        
    }

    public function getBanners()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_screen_id = SiteScreen::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first()->id;

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
            ->orderBy('play_lists.id', 'ASC')
            ->get();

            $banners = $this->listToArray($playlist);

            return $this->response($banners, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Banner to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getFullscreen()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_screen_id = SiteScreen::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first()->id;

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
            ->orderBy('play_lists.id', 'ASC')
            ->get();

            $fullscreens = $this->listToArray($playlist);
            return $this->response($fullscreens, 'Successfully Retreived!', 200);
            
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Fullscreen to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getPromos()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_screen_id = SiteScreen::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first()->id;

            $current_date = date('Y-m-d');

            $playlist = PlayListViewModel::where('play_lists.site_screen_id', $site_screen_id)
            ->where('content_management.active', 1)
            ->where('advertisement_screens.ad_type', '=', 'Promo Page')
            ->whereDate('content_management.start_date', '<=', $current_date)
            ->whereDate('content_management.end_date', '>=', $current_date)
            ->join('content_management', 'play_lists.content_id', '=', 'content_management.id')
            ->join('advertisement_screens', function($join) use ($site_screen_id)
            {
                $join->on('content_management.advertisement_id', '=', 'advertisement_screens.advertisement_id')
                     ->where('advertisement_screens.site_screen_id', '=', $site_screen_id);
            })
            ->select('play_lists.*')
            ->orderBy('play_lists.id', 'ASC')
            ->get();

            $promos = $this->listToArray($playlist);
            $promos = array_chunk($promos, 6);
            
            return $this->response($promos, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Fullscreen to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getCinemas()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_tenants = DirectorySiteTenantViewModel::where('site_tenants.active', 1)
            ->where('brands.name', 'like', '%CINEMA%')
            ->where('brands.name', 'not like', '%LOBBY%')
            ->where('categories.name', 'like', '%Amusement & Exhibitions%')
            ->where('site_tenants.site_id', $site->id)
            ->join('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->join('categories', 'brands.category_id', '=', 'categories.id')
            ->select('site_tenants.*')
            ->distinct()
            ->orderBy('brands.name', 'ASC')
            ->get()->toArray();
            
            $site_tenants = array_chunk($site_tenants, 12);
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }    
    }

    public function getShowing()
    {
        try
        {
            $start_date =  date('Y-m-d 00:00:00');
            $end_date =  date('Y-m-d 23:59:59');

            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            
            $now_showing = CinemaScheduleViewModel::where('show_time', '>=', $start_date)
            ->where('show_time', '<=', $end_date)
            ->where('site_id', $site->id)
            ->groupBy('film_id')
            ->orderBy('title')
            ->get()->toArray();
            
            $now_showing = array_chunk($now_showing, 3);
            return $this->response($now_showing, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }    
    }

    public function getFloors()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();            
            $site_screen = SiteScreenViewModel::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first();            
            $site_floors = SiteMapViewModel::where('site_id', $site->id)->where('site_screen_id', $site_screen->id)
            ->get()->toArray();
            
            return $this->response($site_floors, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }  
    }

    public function getMaps()
    {
        
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_screen = SiteScreenViewModel::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first();  
            $site_maps = SiteMapViewModel::where('site_id', $site->id)->where('site_screen_id', $site_screen->id)->get();
            
            return $this->response($site_maps, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        } 
    }

    public function getPoints($id)
    {        
        try
        {
            $site_points = SitePointViewModel::where('site_map_id', $id)->get();          
            return $this->response($site_points, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        } 
    }

    public function getRoutes($destination_id, $with_disability = 0)
    {
        // try
        // {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();            
            $site_screen = SiteScreenViewModel::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first();
            
            $origin = $this->getPointId($site->id, $site_screen->id);
            $destination = $this->getPointId($site->id, $site_screen->id, $destination_id);

            $coordinates = array();
            $latlng = array();
            $latlng_tmp = SitePoint::where('site_maps.site_id', $site->id)
            ->where('site_maps.site_screen_id', $site_screen->id)
            ->join('site_maps', 'site_points.site_map_id', '=', 'site_maps.id')
            ->select('site_points.id','site_points.point_x as lat', 'site_points.point_y as lng', 'site_maps.site_building_level_id as level', 'site_maps.site_building_id as building', 'site_maps.id as map_id')
            ->get();

            foreach($latlng_tmp as $coordinate) {
                $latlng[$coordinate['id']] = array($coordinate['lat'],$coordinate['lng'],$coordinate['level'],$coordinate['building'],$coordinate['map_id']);
            }

            $map_paths = SiteMapPaths::where('site_id', $site->id)
            ->where('site_screen_id', $site_screen->id)
            ->where('with_disability', $with_disability)
            ->where('point_orig', $origin)
            ->where('point_dest', $destination)
            ->get();

            if(count($map_paths)) {
                $coordinates = array();
                $points = explode('-',$map_paths[0]['path']);
                
                foreach($points as $point)
                {
                    array_push($latlng[$point],$map_paths[0]['distance']);
                    $coordinates[] = $latlng[$point];
                }
            }

            return $this->response($coordinates, 'Successfully Retreived!', 200);
        // }
        // catch (\Exception $e)
        // {
        //     return response([
        //         'message' => 'No Tenants to display!',
        //         'status_code' => 200,
        //     ], 200);
        // } 

    }

    public function getPointId($site_id, $screen_id, $tenant_id = 0)
    {
        try
        {
            $site_point_id = SitePoint::where('site_maps.site_id', $site_id)
            ->where('site_maps.site_screen_id', $screen_id)
            ->when($tenant_id, function($query) use ($tenant_id) {
                $query->where('tenant_id', $tenant_id);
            })
            ->when($tenant_id == 0, function($query) {
                $query->where('point_type', 6);
            })
            ->join('site_maps', 'site_points.site_map_id', '=', 'site_maps.id')
            ->select('site_points.*')
            ->first()->id;

            if($site_point_id)
                return $site_point_id;
            return 0;
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }            
    }

    public function getFloorName($level_id)
    {
        try
        {
            $floor_level = SiteBuildingLevelViewModel::find($level_id);
            if($floor_level)
                return $floor_level;
            return null;
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getBuildingName($building_id)
    {
        try
        {
            $building = SiteBuilding::find($building_id);
            if($building)
                return $building;
            return null;
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getFloorMap($level_id, $buidlind_id)
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();            
            $site_screen = SiteScreenViewModel::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first();

            $site_map = SiteMapViewModel::where('site_building_level_id', $level_id)
            ->where('site_building_id', $buidlind_id)
            ->where('site_screen_id', $site_screen->id)
            ->first();
            
            if($site_map)
                return $site_map;
            return null;
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getLikeCount($site_tenants_id)
    {
        $like_count = SiteTenantViewModel::where('site_tenants.active', 1)
        ->where('site_tenants.id', $site_tenants_id)
        ->value('site_tenants.like_count');

        return $this->response($like_count, 'Successfully Retreived!', 200);
    }

    public function putLikeCount(Request $request)
    {
        DB::statement("UPDATE site_tenants SET site_tenants.like_count = $request->like_count  where site_tenants.id = $request->id");
    }

    public function putFeedback(Request $request)
    {
        $site = Site::where('is_default', 1)->where('active', 1)->first();
        $site_screen_id = SiteScreen::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first()->id;     
        SiteFeedback::Create(
            [
               'site_id' => $site->id,
               'site_screen_id' => $site_screen_id,
               'helpful' => $request->helpful,
               'reason' => $request->reason,
               'reason_other' => $request->reason_other
            ]
        );
    }

    public function getAssistantMessage()
    {
        $messages = AssistantMessageViewModel::all();

        $collection = collect([]);
        foreach ($messages as $value) {
            $collection->push([
                'location' => $value->location,
                'content' => $value->content,
                'content_language' => $value->content_language,
            ]);
        }
        // dd($collection);
        return $this->response($collection, 'Successfully Retreived!', 200);
    }

    public function getTranslation()
    {
        $translations = TranslationViewModel::all();

        $collection = collect([]);
        foreach ($translations as $value) {
            $collection->push([
                'language' => $value->language,
                'english' => $value->english,
                'translated' => $value->translated,
            ]);
        }
        return $this->response($collection, 'Successfully Retreived!', 200);
    }

    function getLandmark() 
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();            
            $landmark = Landmark::where('site_id', $site->id)->where('active', 1)->get();

            $landmark = $this->listToArray($landmark);
            $landmark = array_chunk($landmark, 6);
            
            return $this->response($landmark, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    function getEvents() 
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();            
            $site_screen = SiteScreen::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first();            
            
            $events = Event::where('site_id', $site->id)->where('active', 1)->get();

            $events = $this->listToArray($events);

            if($site_screen->orientation == 'Portrait') {
                $events = array_chunk($events, 12);
            }
            else {
                $events = array_chunk($events, 8);
            }
            
            return $this->response($events, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }
    }

}
