<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\FloorsControllerInterface;
use Illuminate\Http\Request;

use App\Models\SiteBuildingLevel;
use App\Models\SiteMap;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\SiteBuildingLevelViewModel;

class FloorsController extends AppBaseController implements FloorsControllerInterface
{
    /********************************************
    * 			BUILDING FLOORS MANAGEMENT	 	*
    ********************************************/
    public function __construct()
    {
        $this->module_id = 13; 
        $this->module_name = 'Sites Management';
    }

    public function list(Request $request)
    {
        try
        {
            $site_id = session()->get('site_id');

            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $buildings = SiteBuildingLevelViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%');
            })
            ->where('site_id', $site_id)
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($buildings, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function details($id)
    {
        try
        {
            $building_level = SiteBuildingLevelViewModel::find($id);
            return $this->response($building_level, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function store(Request $request)
    {
        try
    	{
            $site_id = session()->get('site_id');
            $data = [
                'site_id' => $site_id,
                'site_building_id' => $request->site_building_id,
                'name' => $request->name,
                'active' => 1
            ];

            $building = SiteBuildingLevel::create($data);
            $building->saveMap($request);

            return $this->response($building, 'Successfully Created!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function update(Request $request)
    {
        try
    	{
            $building_level = SiteBuildingLevel::find($request->id);
            $data = [
                'site_building_id' => $request->site_building_id,
                'name' => $request->name,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $building_level->update($data);
            $building_level->saveMap($request);

            return $this->response($building_level, 'Successfully Created!', 200);            
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function delete($id)
    {
        try
    	{
            $building = SiteBuildingLevel::find($id);
            $site_map = SiteMap::where('site_building_level_id', $id);
            $building->delete();
            $site_map->delete();
            return $this->response($building, 'Successfully Deleted!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function getFloors($id)
    {
        try
    	{
            $site_id = session()->get('site_id');
            $building_levels = SiteBuildingLevel::where('site_id', $site_id)->where('site_building_id', $id)->get();
            return $this->response($building_levels, 'Successfully Deleted!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

}
