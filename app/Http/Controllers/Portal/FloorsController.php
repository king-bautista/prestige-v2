<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\FloorsControllerInterface;
use Illuminate\Http\Request;

use App\Models\SiteBuildingLevel;
use App\Models\SiteMap;
use App\Models\ViewModels\SiteBuildingLevelViewModel;

class FloorsController extends AppBaseController implements FloorsControllerInterface
{
    /********************************************
    * 			BUILDING FLOORS MANAGEMENT	 	*
    ********************************************/
    public function __construct()
    {
        $this->module_id = 53; 
        $this->module_name = 'Property Details';
    }

    public function list(Request $request)
    {
        try
        {
            $site_id = session()->get('site_id');
            $buildings = SiteBuildingLevelViewModel::when(request('search'), function($query){
                return $query->where('site_building_levels.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('site_buildings.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('site_buildings.descriptions', 'LIKE', '%' . request('search') . '%');
            })
            ->leftJoin('site_buildings', 'site_building_levels.site_building_id', '=', 'site_buildings.id')
            ->where('site_building_levels.site_id', $site_id)
            ->select('site_building_levels.*')
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($buildings, 'Successfully Retreived!', 200);
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
            $building_level = SiteBuildingLevelViewModel::find($id);
            return $this->response($building_level, 'Successfully Retreived!', 200);
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
            $site_id = session()->get('site_id');
            $data = [
                'site_id' => $site_id,
                'site_building_id' => $request->site_building_id,
                'name' => $request->name,
                'active' => 1
            ];

            $building = SiteBuildingLevel::create($data);

            return $this->response($building, 'Successfully Created!', 200);
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
            $building_level = SiteBuildingLevel::find($request->id);
            $data = [
                'site_building_id' => $request->site_building_id,
                'name' => $request->name,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $building_level->update($data);

            return $this->response($building_level, 'Successfully Created!', 200);            
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
            $building = SiteBuildingLevel::find($id);
            $building->delete();
            return $this->response($building, 'Successfully Deleted!', 200);
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

    public function getFloors($id)
    {
        try
    	{
            $building_levels = SiteBuildingLevel::where('site_building_id', $id)->get();
            return $this->response($building_levels, 'Successfully Retreived!', 200);
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
