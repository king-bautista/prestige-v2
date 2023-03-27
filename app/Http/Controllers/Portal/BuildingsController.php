<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\BuildingsControllerInterface;
use Illuminate\Http\Request;

use App\Models\SiteBuilding;
use App\Models\ViewModels\SiteViewModel;

class BuildingsController extends AppBaseController implements BuildingsControllerInterface
{
    /********************************************
    * 			SITES BUILDING MANAGEMENT	 	*
    ********************************************/
    public function __construct()
    {
        $this->module_id = 53; 
        $this->module_name = 'Property Details';
    }

    public function index($id)
    {
        session()->forget('site_id');
        session()->put('site_id', $id);
        $site_details = SiteViewModel::find($id);
        return view('portal.site_details', compact("site_details"));
    }

    public function list(Request $request)
    {
        try
        {
            $site_id = session()->get('site_id');
            $buildings = SiteBuilding::when(request('search'), function($query){
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
                'status_code' => 422,
            ], 422);
        }
    }

    public function details($id)
    {
        try
        {
            $building = SiteBuilding::find($id);
            return $this->response($building, 'Successfully Retreived!', 200);
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
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'active' => 1
            ];

            $building = SiteBuilding::create($data);

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
            $building = SiteBuilding::find($request->id);

            $data = [
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $building->update($data);

            return $this->response($building, 'Successfully Modified!', 200);
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
            $building = SiteBuilding::find($id);
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

    public function getAll()
    {
        try
    	{
            $site_id = session()->get('site_id');
            $buildings = SiteBuilding::where('site_id', $site_id)->get();
            return $this->response($buildings, 'Successfully Deleted!', 200);
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

    public function getBuildings($id)
    {
        try
    	{
            $buildings = SiteBuilding::where('site_id', $id)->get();
            return $this->response($buildings, 'Successfully Deleted!', 200);
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
