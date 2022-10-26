<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\SiteTenantsControllerInterface;
use Illuminate\Http\Request;

use App\Models\SiteTenant;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\SiteTenantViewModel;

class SiteTenantsController extends AppBaseController implements SiteTenantsControllerInterface
{
    /********************************************
    * 			SITES TENANTS MANAGEMENT	 	*
    ********************************************/
    public function __construct()
    {
        $this->module_id = 36; 
        $this->module_name = 'Tenants';
    }

    public function index()
    {
        return view('admin.tenants');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $site_tenants = SiteTenantViewModel::when(request('search'), function($query){
                return $query->where('site_buildings.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('brands.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('site_building_levels.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('sites.name', 'LIKE', '%' . request('search') . '%');
            })
            ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->leftJoin('sites', 'site_tenants.site_id', '=', 'sites.id')
            ->leftJoin('site_buildings', 'site_tenants.site_building_id', '=', 'site_buildings.id')
            ->leftJoin('site_building_levels', 'site_tenants.site_building_level_id', '=', 'site_building_levels.id')
            ->select('site_tenants.*')
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($site_tenants, 'Successfully Retreived!', 200);
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
            $site_tenant = SiteTenantViewModel::find($id);
            return $this->response($site_tenant, 'Successfully Retreived!', 200);
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
            $data = [
                'brand_id' => $request->brand_id['id'],
                'site_id' => $request->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'active' => ($request->active) ? 1 : 0,
                'is_subscriber' => ($request->is_subscriber) ? 1 : 0,
            ];

            $site_tenant = SiteTenant::create($data);

            return $this->response($site_tenant, 'Successfully Created!', 200);
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
            $site_tenant = SiteTenant::find($request->id);
            $data = [
                'brand_id' => $request->brand_id['id'],
                'site_id' => $request->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'active' => ($request->active) ? 1 : 0,
                'is_subscriber' => ($request->is_subscriber) ? 1 : 0,
            ];

            $site_tenant->update($data);

            return $this->response($site_tenant, 'Successfully Modified!', 200);
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
            $site_tenant = SiteTenant::find($id);
            $site_tenant->delete();
            return $this->response($site_tenant, 'Successfully Deleted!', 200);
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

    public function getTenants($ids)
    {
        try
    	{
            $ids = explode(",", substr($ids, 0, -1));
            $site_tenants = SiteTenantViewModel::whereIn('site_id', $ids)->get();
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
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

    public function getTenantPerFloor($id)
    {
        try
    	{
            $site_tenants = SiteTenantViewModel::where('site_building_level_id', $id)->get();
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
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
