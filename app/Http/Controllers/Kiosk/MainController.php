<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

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
use App\Models\Site;
use App\Models\SitePoint;
use App\Models\SiteMapPaths;
use App\Models\SiteBuilding;

class MainController extends AppBaseController
{
    public function index()
    {
        $site = Site::where('is_default', 1)->where('active', 1)->first();
        return view('kiosk.main',$site);
    }

    public function getSite()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
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
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $categories = DirectoryCategoryViewModel::getMainCategory($site->id);            
            return $this->response($categories, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Categories to display!',
                'status_code' => 200,
            ], 200);
        }
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
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_tenants = DirectorySiteTenantViewModel::where('site_tenants.active', 1)
            ->where('brands.category_id', $category_id)
            ->where('site_tenants.site_id', $site->id)
            ->join('brands', 'site_tenants.brand_id', '=', 'brands.id')
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

    public function getTenantsBySupplementals($category_id)
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_tenants = SiteTenantViewModel::where('site_tenants.active', 1)
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
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_tenants = SiteTenantViewModel::where('site_tenants.active', 1)
            ->where('site_tenants.site_id', $site->id)
            ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
            ->select('brands.name')
            ->orderBy('brands.name', 'ASC')
            ->get()->pluck('name');
            
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
            $array_words = explode(' ', $request->key_words);

            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $site_tenants = SiteTenantViewModel::where('site_tenants.active', 1)
            ->where(function ($query) use($array_words) {
                foreach($array_words as $key) {
                    $query->orWhere('brands.name', 'like', '%'.$key.'%')
                    ->orWhere('categories.name', 'like', '%'.$key.'%')
                    ->orWhere('supp.name', 'like', '%'.$key.'%')
                    ->orWhere('tags.name', 'like', '%'.$key.'%');
                }
            })
            ->where('site_tenants.site_id', $site->id)
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
            $site_tenants = array_chunk($site_tenants->toArray(), 15);

            return $this->response($site_tenants, 'Successfully Retreived!', 200, $counts);
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

            $current_date = date('Y-m-d');
            $contents = ContentManagementViewModel::where('site_id', $site->id)
            ->where('status_id', 5)
            ->where('active', 1)
            ->whereDate('start_date', '<=', $current_date)
            ->whereDate('end_date', '>=', $current_date)
            ->get();

            $banners = $this->listToArray($contents->where('ad_type', 'Banners'));

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
            
            $current_date = date('Y-m-d');
            $contents = ContentManagementViewModel::where('site_id', $site->id)
            ->where('status_id', 5)
            ->where('active', 1)
            ->whereDate('start_date', '<=', $current_date)
            ->whereDate('end_date', '>=', $current_date)
            ->get();

            $fullscreens = $this->listToArray($contents->where('ad_type', 'Fullscreen'));
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
            
            $current_date = date('Y-m-d');
            $contents = ContentManagementViewModel::where('site_id', $site->id)
            ->where('status_id', 5)
            ->where('active', 1)
            ->whereDate('start_date', '<=', $current_date)
            ->whereDate('end_date', '>=', $current_date)
            ->get();

            $promos = $this->listToArray($contents->where('ad_type', 'Promos'));
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
            ->where('categories.name', 'like', '%Amusement & Exhibitions%')
            ->where('site_tenants.site_id', $site->id)
            ->join('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->join('categories', 'brands.category_id', '=', 'categories.id')
            ->select('site_tenants.*')
            ->distinct()
            ->orderBy('brands.name', 'ASC')
            ->get()->toArray();
            
            $site_tenants = array_chunk($site_tenants, 10);
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
            $site_maps = SiteMapViewModel::where('site_id', $site->id)->get();
            
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

    public function getRoutes($destination_id)
    {
        try
        {
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

            $map_paths = SiteMapPaths::where('site_id', $site->id)->where('point_orig', $origin)->where('point_dest', $destination)->get();

            if(count($map_paths)) {
                $coordinates = array();
                $points = explode('-',$map_paths[0]['path']);
                
                foreach($points as $point)
                {
                    $coordinates[] = $latlng[$point];
                }
            }

            return $this->response($coordinates, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        } 

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
            $site_map = SiteMapViewModel::where('site_building_level_id', $level_id)
            ->where('site_building_id', $buidlind_id)
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

}
