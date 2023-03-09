<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\SiteTenantsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Models\SiteTenant;
use App\Models\SiteTenantProduct;
use App\Models\ViewModels\UserViewModel;
use App\Models\ViewModels\SiteTenantViewModel;
use App\Models\ViewModels\BrandProductViewModel;
use App\Models\ViewModels\TenantsDropdownViewModel;

use App\Imports\SiteTenantsImport;

class SiteTenantsController extends AppBaseController implements SiteTenantsControllerInterface
{
    /********************************************
    * 			SITES TENANTS MANAGEMENT	 	*
    ********************************************/
    public function __construct()
    {
        $this->module_id = 54;
        $this->module_name = 'Tenant Management';
    }

    public function index()
    {
        return view('portal.tenants');
    }

    public function products($id)
    {
        $tenant_details = SiteTenantViewModel::find($id);
        return view('admin.tenant_product', compact("tenant_details"));
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = UserViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

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
            $subscriber_logo = $request->file('subscriber_logo');
            $subscriber_logo_path = '';
            if($subscriber_logo) {
                $originalname = $subscriber_logo->getClientOriginalName();
                $subscriber_logo_path = $subscriber_logo->move('uploads/media/subscriber/', str_replace(' ','-', $originalname)); 
            }

            $brand_id = json_decode($request->brand_id, 1);
            $data = [
                'brand_id' => $brand_id['id'],
                'site_id' => $request->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'company_id' => $request->company_id,
                'active' => ($request->active) ? 1 : 0,
                'is_subscriber' => ($request->is_subscriber == 'true') ? 1 : 0,
            ];

            $site_tenant = SiteTenant::create($data);

            $meta_details = [
                "address" => $request->address, 
                "email" => $request->email,
                "contact_number" => $request->contact_number,
                "facebook" => $request->facebook,
                "twitter" => $request->twitter,
                "instagram" => $request->instagram,
                "website" => $request->website,
                "schedules" => $request->operational_hours,
                "subscriber_logo" => str_replace('\\', '/', $subscriber_logo_path)
            ];
            $site_tenant->saveMeta($meta_details);

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
            $site_tenant->touch();

            $subscriber_logo = $request->file('subscriber_logo');
            $subscriber_logo_path = '';
            if($subscriber_logo) {
                $originalname = $subscriber_logo->getClientOriginalName();
                $subscriber_logo_path = $subscriber_logo->move('uploads/media/subscriber/', str_replace(' ','-', $originalname)); 
            }

            $brand_id = json_decode($request->brand_id, 1);
            $data = [
                'brand_id' => $brand_id['id'],
                'site_id' => $request->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'company_id' => $request->company_id,
                'active' => ($request->active) ? 1 : 0,
                'is_subscriber' => ($request->is_subscriber) ? 1 : 0,
            ];

            $site_tenant->update($data);

            $meta_details = [
                "address" => $request->address, 
                "email" => $request->email,
                "contact_number" => $request->contact_number,
                "facebook" => $request->facebook,
                "twitter" => $request->twitter,
                "instagram" => $request->instagram,
                "website" => $request->website,
            ];

            if($request->operational_hours)
                $meta_details["schedules"] = $request->operational_hours;
            
            if($subscriber_logo_path)
                $meta_details["subscriber_logo"] = str_replace('\\', '/', $subscriber_logo_path);

            $site_tenant->saveMeta($meta_details);

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
            $ids = explode(",", rtrim($ids, ","));
            $site_tenants = TenantsDropdownViewModel::whereIn('site_id', $ids)->get();
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

    public function batchUpload(Request $request)
    {
        try
        {
            Excel::import(new SiteTenantsImport, $request->file('file'));
            return $this->response(true, 'Successfully Uploaded!', 200);  
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

    public function tenantProducts($id)
    {
        try
        {
            $this->permissions = UserViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $products = BrandProductViewModel::when(request('search'), function($query){
                return $query->where('brand_products_promos.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('brand_products_promos.descriptions', 'LIKE', '%' . request('search') . '%');
            })
            ->where('site_tenant_products.site_tenant_id', $id)
            ->join('site_tenant_products', 'brand_products_promos.id', '=', 'site_tenant_products.brand_product_promo_id')
            ->select('brand_products_promos.*')
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($products, 'Successfully Retreived!', 200);
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

    public function deleteProduct($tenant_id, $id)
    {
        try
    	{
            $tenant_product = SiteTenantProduct::where('site_tenant_id', $tenant_id)->where('brand_product_promo_id', $id)->delete();
            return $this->response($tenant_product, 'Successfully Deleted!', 200);
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

    public function saveBrandProduct(Request $request)
    {
        try
        {
            $tenant_product = SiteTenant::find($request->site_tenant_id);
            $tenant_product->touch();
            $tenant_product->saveProducts($request->product_ids);

            return $this->response($tenant_product, 'Successfully saved!', 200);
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
