<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\MapsControllerInterface;
use Illuminate\Http\Request;

use App\Models\Amenity;
use App\Models\SitePoint;
use App\Models\SiteScreen;
use App\Models\SiteMap;
use App\Models\SitePointLink;
use App\Models\ViewModels\SiteViewModel;
use App\Models\ViewModels\SiteMapViewModel;
use App\Models\ViewModels\SiteTenantViewModel;
use App\Models\ViewModels\SiteScreenViewModel;
use App\Models\ViewModels\AdminViewModel;

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
        session()->forget('site_screen_id');
        session()->put('site_screen_id', $id);
        $site_screen = SiteScreenViewModel::find($id);
        return view('admin.manage_map', compact("site_screen"));
    }

    public function list(Request $request)
    {
        try
        {
            $site_screen_id = session()->get('site_screen_id');

            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $site_maps = SiteMapViewModel::when(request('search'), function($query){
                return $query->where('map_file', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%');
            })
            ->where('site_screen_id', $site_screen_id)
            ->latest()
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
            $site_screen_id = session()->get('site_screen_id');
            $site_screen = SiteScreenViewModel::find($site_screen_id);

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
                'site_id' => $site_screen->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'site_screen_id' => $site_screen_id,
                'image_size_width' => $width,
                'image_size_height' => $height,
                'descriptions' => $request->name,
                'position_x' => $request->position_x,
                'position_y' => $request->position_y,
                'position_z' => $request->position_z,
                'text_y_position' => $request->text_y_position,
                'default_zoom' => $request->default_zoom,
                'default_zoom_desktop' => $request->default_zoom_desktop,
                'default_zoom_mobile' => $request->default_zoom_mobile,
                'map_file' => str_replace('\\', '/', $map_file_path),
                'map_preview' => str_replace('\\', '/', $map_preview_path),
                'active' => ($request->active == 'false') ? 0 : 1,
                'is_default' => ($request->is_default == 'false') ? 0 : 1,
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
            session()->put('site_screen_id', $site_map->site_screen_id);

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
                'image_size_width' => $width,
                'image_size_height' => $height,
                'descriptions' => $request->name,
                'position_x' => $request->position_x,
                'position_y' => $request->position_y,
                'position_z' => $request->position_z,
                'text_y_position' => $request->text_y_position,
                'default_zoom' => $request->default_zoom,
                'default_zoom_desktop' => $request->default_zoom_desktop,
                'default_zoom_mobile' => $request->default_zoom_mobile,
                'map_file' => ($map_file_path) ? str_replace('\\', '/', $map_file_path) : $site_map->map_file,
                'map_preview' => ($map_preview_path) ? str_replace('\\', '/', $map_preview_path) : $site_map->map_preview,
                'active' => ($request->active == 'false') ? 0 : 1,
                'is_default' => ($request->is_default == 'false') ? 0 : 1,
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
        $site_id = session()->get('site_id');
        $site_details = SiteViewModel::find($site_id);
        $site_maps = SiteMapViewModel::where('site_id', $site_id)->get();
        $current_map = SiteMapViewModel::find($id);
        $amenities = Amenity::get();
        $site_tenants = SiteTenantViewModel::where('site_building_level_id', $current_map->site_building_level_id)->get();
        
        return view('admin.map', compact(['site_details', 'site_maps', 'current_map', 'amenities', 'site_tenants']));
    }

    public function getSitePoints($id)
    {
        try
    	{
            $site_points = SitePoint::where('site_map_id', $id)->get();
            return $this->response($site_points, 'Successfully Created!', 200);
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

    public function deletePoint(Request $request)
    {
        try
    	{
            $site_point = SitePoint::find($request->id);
            $site_point->delete();

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
            $site_point = SitePoint::find($id);
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

}
