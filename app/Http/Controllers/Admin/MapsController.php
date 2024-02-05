<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\MapsControllerInterface;
use Illuminate\Http\Request;
use App\Helpers\DijkstraHelper;
use Illuminate\Support\Str;

use App\Models\Amenity;
use App\Models\SitePoint;
use App\Models\SiteScreen;
use App\Models\SiteMap;
use App\Models\SitePointLink;
use App\Models\SiteMapPaths;
use App\Models\SiteMapConfig;
use App\Models\ViewModels\SitePointViewModel;
use App\Models\ViewModels\SitePointLinkViewModel;

use App\Models\AdminViewModels\SiteTenantViewModel;
use App\Models\AdminViewModels\SiteMapViewModel;
use App\Models\AdminViewModels\SiteScreenViewModel;
use App\Models\AdminViewModels\SiteViewModel;
use App\Models\AdminViewModels\SiteMapConfigViewModel;

class MapsController extends AppBaseController implements MapsControllerInterface
{
    /********************************
    * 			MAP MANAGEMENT	 	*
    ********************************/
    public function __construct()
    {
        $this->module_id = 13; 
        $this->module_name = 'Sites Management';
    }

    public function index($id)
    {
        $site = SiteViewModel::find($id);
        return view('admin.manage_map', compact("site"));
    }

    public function mapConfig($id) 
    {
        $site = SiteViewModel::find($id);
        return view('admin.manage_map_config', compact("site"));        
    }

    public function list($id)
    {
        try
        {
            $site_maps = SiteMapViewModel::when(request('search'), function($query){
                return $query->where('map_file', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%');
            })
            ->where('site_id', $id)
            ->paginate(request('perPage'));
            return $this->responsePaginate($site_maps, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function details($id)
    {
        try
        {
            $site_map = SiteMapViewModel::find($id);
            return $this->response($site_map, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(Request $request)
    {
        try
    	{
            $map_file = $request->file('map_file');
            $map_file_path = '';
            $width = '';
            $height = '';
            
            if($map_file) {
                $originalname = $map_file->getClientOriginalName();
                $map_file_path = $map_file->move('uploads/map/files/', str_replace(' ','-', $originalname)); 
            }

            $map_preview = $request->file('map_preview');
            $map_preview_path = '';
            if($map_preview) {
                $originalname = $map_preview->getClientOriginalName();
                $map_preview_path = $map_preview->move('uploads/map/preview/', str_replace(' ','-', $originalname)); 
                $imagesize = getimagesize($map_preview_path);
                $width = $imagesize[0]; 
                $height = $imagesize[1];
            }

            $data = [
                'site_id' => $request->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'map_type' => $request->map_type,
                'map_file' => str_replace('\\', '/', $map_file_path),
                'map_preview' => str_replace('\\', '/', $map_preview_path),
                'image_size_width' => $width,
                'image_size_height' => $height,
                'active' => $this->checkBolean($request->active),
            ];

            $site_map = SiteMap::create($data);
            return $this->response($site_map, 'Successfully Created!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(Request $request)
    {
        try
    	{
            $site_map = SiteMap::find($request->id);

            $map_file = $request->file('map_file');
            $map_file_path = '';
            $width = '';
            $height = '';
            
            if($map_file) {
                $originalname = $map_file->getClientOriginalName();
                $map_file_path = $map_file->move('uploads/map/files/', str_replace(' ','-', $originalname)); 
                $imagesize = getimagesize($map_file_path);
                $width = $imagesize[0]; 
                $height = $imagesize[1];
            }

            $map_preview = $request->file('map_preview');
            $map_preview_path = '';
            if($map_preview) {
                $originalname = $map_preview->getClientOriginalName();
                $map_preview_path = $map_preview->move('uploads/map/preview/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'site_id' => $request->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'map_type' => $request->map_type,
                'map_file' => ($map_file_path) ? str_replace('\\', '/', $map_file_path) : $site_map->map_file,
                'map_preview' => ($map_preview_path) ? str_replace('\\', '/', $map_preview_path) : $site_map->map_preview,
                'image_size_width' => ($width) ? $width : $site_map->image_size_width,
                'image_size_height' => ($height) ? $height : $site_map->image_size_height,
                'active' => $this->checkBolean($request->active),
            ];

            $site_map->update($data);

            return $this->response($site_map, 'Successfully Modified!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function delete($id)
    {
        try
    	{
            $site_map = SiteMap::find($id);
            $site_map->delete();
            return $this->response($site_map, 'Successfully Deleted!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getMapDetails($id)
    {
        $current_map = SiteMapViewModel::find($id);
        $amenities = Amenity::get();
        $site_details = SiteViewModel::find($current_map->site_id);
        $site_tenants = SiteTenantViewModel::where('site_building_level_id', $current_map->site_building_level_id)->get();
        $map_points = $this->getMapPoints($current_map->site_id, $current_map->map_type);

        if($current_map->map_type == '3D') {
            $site_maps = SiteMapViewModel::where('site_id', $current_map->site_id)->where('map_type', '3D')->get();
            return view('admin.map_3d', compact(['site_details', 'site_maps', 'current_map', 'amenities', 'site_tenants', 'map_points']));    
        }
        else {
            $site_maps = SiteMapViewModel::where('site_id', $current_map->site_id)->where('site_screen_id', $current_map->site_screen_id)->get();
            return view('admin.map', compact(['site_details', 'site_maps', 'current_map', 'amenities', 'site_tenants', 'map_points']));
        }        
    }

    public function getMapPoints($site_id, $map_type) {
        $new_map_points = [];

        $map_points = SitePointViewModel::where('site_maps.site_id', $site_id)
        ->where('site_maps.map_type', $map_type)
        ->join('site_maps', 'site_points.site_map_id', '=', 'site_maps.id')
        ->select('site_points.*')->get();

        foreach($map_points as $map_point) {
            $new_map_points[$map_point->site_map_id][] = $map_point;
        }

        return json_encode($new_map_points);
    }

    public function getSitePoints($id)
    {
        try
    	{
            $site_points = SitePointViewModel::where('site_map_id', $id)->get();
            return $this->response($site_points, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getSiteLinks($id)
    {
        try
    	{
            $site_links = SitePointLinkViewModel::where('site_point_links.site_map_id', $id)
            ->join('site_points as a', function($join) use ($id)
             {
                 $join->on('site_point_links.point_a', '=', 'a.id')
                      ->where('a.site_map_id','=', $id);
             })
             ->join('site_points as b', function($join) use ($id)
             {
                 $join->on('site_point_links.point_b', '=', 'b.id')
                      ->where('b.site_map_id','=', $id);
             })
             ->get();

            return $this->response($site_links, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function createPoint(Request $request)
    {
        try
    	{
            $data = [
                'site_map_id' => $request->map_id,
                'point_x' => $request->point_x,
                'point_y' => $request->point_y,
                'point_z' => $request->point_z,
                'wrap_at' => 0,
            ];

            $site_point = SitePoint::create($data);

            return $this->response($site_point, 'Successfully Created!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function updatePoint(Request $request)
    {
        try
    	{
            $site_point = SitePoint::find($request->id);
            $data = [
                'point_x' => $request->point_x,
                'point_y' => $request->point_y,
                'point_z' => $request->point_z,
            ];

            $site_point->update($data);

            return $this->response($site_point, 'Successfully Modified!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function deletePoint($id)
    {
        try
    	{
            $site_point = SitePoint::find($id);
            $site_point->delete();

            // SitePointLink::where('point_a', $id)->delete();
            // SitePointLink::where('point_b', $id)->delete();
            return $this->response($site_point, 'Successfully Deleted!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function pointInfo($id)
    {
        try
    	{
            $site_point = SitePointViewModel::find($id);
            return $this->response($site_point, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function connectPoints(Request $request)
    {
        try
    	{
            $data = [
                'site_map_id' => $request->map_id,
                'point_a' => $request->point_a,
                'point_b' => $request->point_b
            ];
            $site_point_link = SitePointLink::create($data);
            return $this->response($site_point_link, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function updatePointDetails(Request $request)
    {
        try
    	{
            $site_point = SitePoint::find($request->pid);
            $data = [
                'point_x' => ($request->position_x) ? $request->position_x : 0,
                'point_y' => ($request->position_y) ? $request->position_y : 0,
                'rotation_z' => ($request->text_y_position) ? $request->text_y_position : 0,
                'text_size' => ($request->text_size) ? $request->text_size : 0,
                'text_width' => ($request->text_width) ? $request->text_width : 0,
                'is_pwd' => ($request->is_pwd) ? $request->is_pwd : 0,
                'wrap_at' => ($request->wrap_at == 1) ? 1 : 0,
                'tenant_id' => ($request->tenant_id) ? $request->tenant_id : 0,
                'point_type' => ($request->point_type) ? $request->point_type : 0,
                'point_label' => ($request->point_label) ? $request->point_label : null,
            ];

            $site_point->update($data);

            return $this->response($site_point, 'Successfully Modified!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function deleteLine($id)
    {
        try
    	{
            $site_point_link = SitePointLink::find($id);
            $site_point_link->delete();

            return $this->response($site_point_link, 'Successfully Deleted!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getOrigin($site_id, $screen_id)
    {
        $origin = SitePoint::where('site_maps.site_id', $site_id)
        ->where('site_maps.site_screen_id', $screen_id)
        ->where('point_type', 6)
        ->join('site_maps', 'site_points.site_map_id', '=', 'site_maps.id')
        ->select('site_points.*')
        ->first();

        if($origin)
            return $origin->id;
        return 0;
    }

    public function pointDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
	{
		return sqrt(pow(($latitudeTo-$latitudeFrom),2) + pow(($longitudeTo-$longitudeFrom),2));
	}

    public function generateRoutes($site_id, $screen_id)
    {
        // try {
            $this->createRoutes($site_id, $screen_id);
            $this->createRoutes($site_id, $screen_id, 1);

            return $this->response(true, 'Successfully Modified!', 200);
        // } 
        // catch (\Exception $e) 
        // {
        //     return response([
        //         'message' => $e->getMessage(),
        //         'status' => false,
        //         'status_code' => 422,
        //     ], 422);
        // }
    }

    public function createRoutes($site_id, $screen_id, $with_disability = 0)
    {
        $origin = $this->getOrigin($site_id, $screen_id);
        
        $amenities = Amenity::whereIn('name', ['Escalator','Stairs'])->get()->pluck('id');

        $latlng = array();
        $latlng_tmp = SitePoint::where('site_maps.site_id', $site_id)
        ->where('site_maps.site_screen_id', $screen_id)
        ->when($with_disability, function ($query) use ($amenities) {
            return $query->whereNotIn('site_points.point_type', $amenities);
        })
        ->when(!$with_disability, function ($query) {
            return $query->whereNotIn('site_points.point_type', [9]);
        })
        ->join('site_maps', 'site_points.site_map_id', '=', 'site_maps.id')
        ->select('site_points.id','site_points.point_x as lat', 'site_points.point_y as lng')
        ->get();

        $points_to_index = array();
        $index_to_points = array();
        $ctr = 0;

        foreach($latlng_tmp as $row)
        {
            $points_to_index[$row['id']] = $ctr;
            $index_to_points[$ctr] = $row['id'];
            $latlng[$row['id']] = array($row['lat'],$row['lng']);
            $ctr++;
        }

        $network = array();
        $point_links = SitePointLink::where('site_maps.site_id', $site_id)
        ->where('site_maps.site_screen_id', $screen_id)
        ->join('site_maps', 'site_point_links.site_map_id', '=', 'site_maps.id')
        ->get();

        $max = 0;
        foreach($point_links as $link) {                
            if(isset($latlng[$link['point_a']]) && isset($latlng[$link['point_b']])) {
                $distance = $this->pointDistance($latlng[$link['point_a']][0],
                                                $latlng[$link['point_a']][1],
                                                $latlng[$link['point_b']][0],
                                                $latlng[$link['point_b']][1]);
                
                $network[] = array($points_to_index[$link['point_a']],$points_to_index[$link['point_b']],$distance);

                $max = $latlng[$link['point_a']][0] > $max ? $latlng[$link['point_a']][0]:  $max;
                $max = $latlng[$link['point_a']][1] > $max ? $latlng[$link['point_a']][1]:  $max;
                $max = $latlng[$link['point_b']][0] > $max ? $latlng[$link['point_b']][0]:  $max;
                $max = $latlng[$link['point_b']][1] > $max ? $latlng[$link['point_b']][1]:  $max;

            }
        }

        // Size of the matrix
        $matrixWidth = $max;
        
        $ourMap = array();
        $points = $network;

        // Read in the points and push them into the map
        for ($i=0,$m=count($points); $i<$m; $i++)
        {
            $x = $points[$i][0];
            $y = $points[$i][1];
            $c = $points[$i][2];
            $ourMap[$x][$y] = $c;
            $ourMap[$y][$x] = $c;
        }

        // ensure that the distance from a node to itself is always zero
        // Purists may want to edit this bit out.
        for ($i=0; $i < $matrixWidth; $i++) {
            for ($k=0; $k < $matrixWidth; $k++) {
                if ($i == $k) $ourMap[$i][$k] = 0;
            }
        }

        $dijkstra = new DijkstraHelper($ourMap, $index_to_points, I, $site_id, $screen_id, $with_disability);
        $dijkstra->findShortestPath($points_to_index[$origin]); 
        $routes = $dijkstra->getResults();

        // delete old records
        SiteMapPaths::where('site_id', $site_id)->where('site_screen_id', $screen_id)
        ->when($with_disability, function ($query) {
            return $query->where('with_disability', 1);
        })
        ->delete();
        foreach($routes as $route) {
            SiteMapPaths::create($route);
        }
        
        return $this->response($routes, 'Successfully Modified!', 200);
    }

    public function configList($id) {
        try
        {
            $site_maps = SiteMapConfigViewModel::when(request('search'), function($query){
                return $query->where('map_file', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%');
            })
            ->join('site_maps', 'site_map_configs.site_map_id', '=', 'site_maps.id')
            ->where('site_maps.site_id', $id)
            ->select('site_map_configs.*', 'site_maps.map_type')
            ->paginate(request('perPage'));
            return $this->responsePaginate($site_maps, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function configDetails($id)
    {
        try
        {
            $site_map = SiteMapConfigViewModel::find($id);
            return $this->response($site_map, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function configStore(Request $request){
        try
    	{
            $data = [
                'site_map_id' => $request->map_details,
                'site_screen_id' => $request->site_screen_id,
                'start_scale' => ($request->start_scale) ? $request->start_scale : 0,
                'start_x' => ($request->start_x) ? $request->start_x : 0,
                'start_y' => ($request->start_y) ? $request->start_y : 0,
                'default_zoom' => ($request->default_zoom) ? $request->default_zoom : 0,
                'default_x' => ($request->default_x) ? $request->default_x : 0,
                'default_y' => ($request->default_y) ? $request->default_y : 0,
                'name_angle' => ($request->name_angle) ? $request->name_angle : 0,
                'view_angle' => ($request->view_angle) ? $request->view_angle : 0,
                'building_label_height' => ($request->building_label_height) ? $request->building_label_height : 0,
                'building_label_space' => ($request->building_label_space) ? $request->building_label_space : 0,
                'building_animation_height' => ($request->building_animation_height) ? $request->building_animation_height : 0,
                'floor_label_height' => ($request->floor_label_height) ? $request->floor_label_height : 0,
                'floor_label_space' => ($request->floor_label_space) ? $request->floor_label_space : 0,
                'floor_animation_height' => ($request->floor_animation_height) ? $request->floor_animation_height : 0,
                'active' => $this->checkBolean($request->active),
                'is_default' => $this->checkBolean($request->is_default),
            ];

            $site_config = SiteMapConfig::create($data);
            $site_config->serial_number = 'SMC-' . Str::padLeft($site_config->id, 5, '0');
            $site_config->save();
    
            return $this->response($site_config, 'Successfully Created!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function configUpdate(Request $request)
    {
        try
    	{
            $site_config = SiteMapConfig::find($request->id);

            $data = [
                'serial_number' => ($site_config->serial_number) ? $site_config->serial_number : 'SMC-' . Str::padLeft($site_config->id, 5, '0'),
                'site_map_id' => $request->map_details,
                'site_screen_id' => $request->site_screen_id,
                'start_scale' => ($request->start_scale) ? $request->start_scale : 0,
                'start_x' => ($request->start_x) ? $request->start_x : 0,
                'start_y' => ($request->start_y) ? $request->start_y : 0,
                'default_zoom' => ($request->default_zoom) ? $request->default_zoom : 0,
                'default_x' => ($request->default_x) ? $request->default_x : 0,
                'default_y' => ($request->default_y) ? $request->default_y : 0,
                'name_angle' => ($request->name_angle) ? $request->name_angle : 0,
                'view_angle' => ($request->view_angle) ? $request->view_angle : 0,
                'building_label_height' => ($request->building_label_height) ? $request->building_label_height : 0,
                'building_label_space' => ($request->building_label_space) ? $request->building_label_space : 0,
                'building_animation_height' => ($request->building_animation_height) ? $request->building_animation_height : 0,
                'floor_label_height' => ($request->floor_label_height) ? $request->floor_label_height : 0,
                'floor_label_space' => ($request->floor_label_space) ? $request->floor_label_space : 0,
                'floor_animation_height' => ($request->floor_animation_height) ? $request->floor_animation_height : 0,
                'active' => $this->checkBolean($request->active),
                'is_default' => $this->checkBolean($request->is_default),
            ];

            $site_config->update($data);

            return $this->response($site_config, 'Successfully Modified!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function configDelete($id)
    {
        try
    	{
            $site_config = SiteMapConfig::find($id);
            $site_config->delete();
            return $this->response($site_config, 'Successfully Deleted!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function setDefault($id)
    {
        try
    	{
            $site_config = SiteMapConfigViewModel::find($id);
            $site_id = $site_config->map_details->site_id;

            SiteMapConfig::where('site_maps.site_id', $site_id)
            ->join('site_maps', 'site_map_configs.site_map_id', '=', 'site_maps.id')
            ->update(['site_map_configs.is_default' => 0]);

            $site_config->update(['is_default' => 1]);

            return $this->response($site_config, $site_config->site_screen_name. ' has been set as default!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

}
