<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\SiteTenantsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\TenantRequest;
use Illuminate\Support\Str;

use App\Models\SiteTenant;
use App\Models\SiteTenantProduct;
use App\Models\AdminViewModels\SiteTenantViewModel;
use App\Models\AdminViewModels\BrandProductViewModel;
use App\Models\ViewModels\TenantsDropdownViewModel;

use App\Imports\SiteTenantsImport;
use App\Exports\Export;
use Storage;

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

    public function products($id)
    {
        $tenant_details = SiteTenantViewModel::find($id);
        return view('admin.tenant_product', compact("tenant_details"));
    }

    public function list(Request $request)
    {
        try {
            $site_tenants = SiteTenantViewModel::when(request('search'), function ($query) {
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
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function details($id)
    {
        try {
            $site_tenant = SiteTenantViewModel::find($id);
            return $this->response($site_tenant, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(TenantRequest $request)
    {
        try {
            $subscriber_logo = $request->file('subscriber_logo');
            $subscriber_logo_path = '';
            if ($subscriber_logo) {
                $originalname = $subscriber_logo->getClientOriginalName();
                $subscriber_logo_path = $subscriber_logo->move('uploads/media/subscriber/', str_replace(' ', '-', $originalname));
            }

            $brand_id = json_decode($request->brand_id, 1);
            $data = [
                'brand_id' => $brand_id['id'],
                'site_id' => $request->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'company_id' => ($request->company_id) ? $request->company_id : 0,
                'space_number' => ($request->space_number) ? $request->space_number : null,
                'client_locator_number' => ($request->client_locator_number) ? $request->client_locator_number : null,
                'active' => $this->checkBolean($request->active),
                'is_subscriber' => $this->checkBolean($request->is_subscriber),
            ];

            $site_tenant = SiteTenant::create($data);
            $site_tenant->serial_number = 'TN-' . Str::padLeft($site_tenant->id, 5, '0');
            $site_tenant->save();

            $meta_details = [
                "address" => ($request->address) ? $request->address : null,
                "email" => ($request->email) ? $request->email : null,
                "contact_person" => ($request->contact_person) ? $request->contact_person : null,
                "contact_number" => ($request->contact_number) ? $request->contact_number : null,
                "facebook" => ($request->facebook) ? $request->facebook : null,
                "twitter" => ($request->twitter) ? $request->twitter : null,
                "instagram" => ($request->instagram) ? $request->instagram : null,
                "website" => ($request->website) ? $request->website : null,
                "schedules" => ($request->operational_hours) ? $request->operational_hours : null,
                "subscriber_logo" => ($subscriber_logo_path) ? str_replace('\\', '/', $subscriber_logo_path) : null
            ];
            $site_tenant->saveMeta($meta_details);

            return $this->response($site_tenant, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(TenantRequest $request)
    {
        try {
            $site_tenant = SiteTenant::find($request->id);
            $site_tenant->touch();

            $subscriber_logo = $request->file('subscriber_logo');
            $subscriber_logo_path = '';
            if ($subscriber_logo) {
                $originalname = $subscriber_logo->getClientOriginalName();
                $subscriber_logo_path = $subscriber_logo->move('uploads/media/subscriber/', str_replace(' ', '-', $originalname));
            }

            $brand_id = json_decode($request->brand_id, 1);
            $data = [
                'serial_number' => ($site_tenant->serial_number) ? $site_tenant->serial_number : 'TN-' . Str::padLeft($site_tenant->id, 5, '0'),
                'brand_id' => $brand_id['id'],
                'site_id' => $request->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'company_id' => ($request->company_id) ? $request->company_id : 0,
                'space_number' => ($request->space_number) ? $request->space_number : null,
                'client_locator_number' => ($request->client_locator_number) ? $request->client_locator_number : null,
                'active' => $this->checkBolean($request->active),
                'is_subscriber' => $this->checkBolean($request->is_subscriber),
            ];
            $site_tenant->update($data);

            $meta_details = [
                "address" => ($request->address) ? $request->address : null,
                "email" => ($request->email) ? $request->email : null,
                "contact_person" => ($request->contact_person) ? $request->contact_person : null,
                "contact_number" => ($request->contact_number) ? $request->contact_number : null,
                "facebook" => ($request->facebook) ? $request->facebook : null,
                "twitter" => ($request->twitter) ? $request->twitter : null,
                "instagram" => ($request->instagram) ? $request->instagram : null,
                "website" => ($request->website) ? $request->website : null,
                "schedules" => ($request->operational_hours) ? $request->operational_hours : null,
                "subscriber_logo" => ($subscriber_logo_path) ? str_replace('\\', '/', $subscriber_logo_path) : null
            ];
            $site_tenant->saveMeta($meta_details);

            return $this->response($site_tenant, 'Successfully Modified!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function delete($id)
    {
        try {
            $site_tenant = SiteTenant::find($id);
            $site_tenant->delete();
            return $this->response($site_tenant, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTenants($ids)
    {
        try {
            $ids = explode(",", rtrim($ids, ","));
            $site_tenants = TenantsDropdownViewModel::whereIn('site_id', $ids)->get();
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTenantPerFloor($id)
    {
        try {
            $site_tenants = SiteTenantViewModel::where('site_building_level_id', $id)->get();
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function batchUpload(Request $request)
    {
        try {
            Excel::import(new SiteTenantsImport, $request->file('file'));
            return $this->response(true, 'Successfully Uploaded!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function tenantProducts($id)
    {
        try {
            $products = BrandProductViewModel::when(request('search'), function ($query) {
                return $query->where('brand_products_promos.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('brand_products_promos.descriptions', 'LIKE', '%' . request('search') . '%');
            })
                ->where('site_tenant_products.site_tenant_id', $id)
                ->join('site_tenant_products', 'brand_products_promos.id', '=', 'site_tenant_products.brand_product_promo_id')
                ->select('brand_products_promos.*')
                ->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($products, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function deleteProduct($tenant_id, $id)
    {
        try {
            $tenant_product = SiteTenantProduct::where('site_tenant_id', $tenant_id)->where('brand_product_promo_id', $id)->delete();
            return $this->response($tenant_product, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function saveBrandProduct(Request $request)
    {
        try {
            $tenant_product = SiteTenant::find($request->site_tenant_id);
            $tenant_product->touch();
            $tenant_product->saveProducts($request->product_ids);

            return $this->response($tenant_product, 'Successfully saved!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsv()
    {
        try {

            $tenants_management = SiteTenantViewModel::get();
            $reports = [];
            foreach ($tenants_management as $tenant) {
                $reports[] = [
                    'id' => $tenant->id,
                    'serial_number' => $tenant->serial_number,
                    'brand_id' => $tenant->brand_id,
                    'brand_name' => $tenant->brand_name,
                    'brand_logo' => $tenant->brand_logo,
                    'site_id' => $tenant->site_name,
                    'site_name' => $tenant->site_name,
                    'site_building_id' => $tenant->site_building_id,
                    'site_building_name' => $tenant->building_name,
                    'site_building_level_id' => $tenant->floor_name,
                    'company_id' => $tenant->company_id,
                    'company_name' => $tenant->company_name,
                    'space_number' => $tenant->space_number,
                    'client_locator_number' => $tenant->client_locator_number,
                    'view_count' => $tenant->view_count,
                    'like_count' => $tenant->like_count,
                    'active' => $tenant->active,
                    'is_subscriber' => $tenant->is_subscriber,
                    'updated_at' => $tenant->updated_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "tenant-management.csv";
            // Store on default disk
            Excel::store(new Export($reports), $directory . $filename);

            $data = [
                'filepath' => '/storage/export/reports/' . $filename,
                'filename' => $filename
            ];

            if (Storage::exists($directory . $filename))
                return $this->response($data, 'Successfully Retreived!', 200);

            return $this->response(false, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvTemplate()
    {
        try {

            $tenants_management = SiteTenantViewModel::get();
            $reports = [];
            foreach ($tenants_management as $tenant) {
                $reports[] = [
                    'id' => '',
                    'serial_number' => '',
                    'brand_id'=> '',
                    'brand_name'=> '',
                    'brand_logo'=> '',
                    'site_id'=> '',
                    'site_name'=> '',
                    'site_building_id'=> '',
                    'site_building_name'=> '',
                    'site_building_level_id'=> '',
                    'company_id'=> '',
                    'company_name'=> '',
                    'space_number'=> '',
                    'client_locator_number'=> '',
                    'view_count'=> '',
                    'like_count'=> '',
                    'active'=> '',
                    'is_subscriber'=> '',
                    'updated_at'=> '',
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "tenant-management-template.csv";
            // Store on default disk
            Excel::store(new Export($reports), $directory . $filename);

            $data = [
                'filepath' => '/storage/export/reports/' . $filename,
                'filename' => $filename
            ];

            if (Storage::exists($directory . $filename))
                return $this->response($data, 'Successfully Retreived!', 200);

            return $this->response(false, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}
