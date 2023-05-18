<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\AdvertisementControllerInterface;

use Illuminate\Http\Request;
use App\Http\Requests\AdvertisementRequest;

use App\Models\Advertisement;
use App\Models\AdvertisementMaterial;
use App\Models\AdvertisementScreen;
use App\Models\ViewModels\AdvertisementViewModel;
use App\Models\ViewModels\AdvertisementMaterialViewModel;
use App\Models\ViewModels\ContentMaterialViewModel;

class AdvertisementController extends AppBaseController implements AdvertisementControllerInterface
{
    /************************************************
    * 			ADVERTISEMENT ADS MANAGEMENT	 	*
    ************************************************/
    public function __construct()
    {
        $this->module_id = 14; 
        $this->module_name = 'Advertisements';
    }

    public function index()
    {
        return view('admin.advertisements_online');
    }

    public function list(Request $request)
    {
        try
        {
            $advertisements = AdvertisementViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($advertisements, 'Successfully Retreived!', 200);
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
            $advertisement = AdvertisementViewModel::find($id);
            return $this->response($advertisement, 'Successfully Retreived!', 200);
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

    public function store(AdvertisementRequest $request)
    {
        try
    	{
            $company_id = json_decode($request->company_id);
            $contract_id = json_decode($request->contract_id);
            $brand_id = json_decode($request->brand_id);
            $status_id = json_decode($request->status_id);

            $data = [
                'company_id' => ($company_id) ? $company_id->id : null,
                'contract_id' => ($contract_id) ? $contract_id->id : null,
                'brand_id' => ($brand_id) ? $brand_id->id : null,
                'status_id' => ($status_id) ? $status_id->id : null,
                'product_application' => $request->product_application,
                'display_duration' => $request->display_duration,
                'name' => $request->name,
                'active' => ($request->active == 'false') ? 0 : 1
            ];

            $advertisement = Advertisement::create($data);
            $advertisement->saveMaterials(json_decode($request->materials), $request->file('files'));

            return $this->response($advertisement, 'Successfully Created!', 200);
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

    public function update(AdvertisementRequest $request)
    {
        try
    	{
            $advertisement = Advertisement::find($request->id);
            $advertisement->touch();

            $company_id = json_decode($request->company_id);
            $contract_id = json_decode($request->contract_id);
            $brand_id = json_decode($request->brand_id);
            $status_id = json_decode($request->status_id);

            $data = [
                'company_id' => ($company_id) ? $company_id->id : null,
                'contract_id' => ($contract_id) ? $contract_id->id : null,
                'brand_id' => ($brand_id) ? $brand_id->id : null,
                'status_id' => ($status_id) ? $status_id->id : null,
                'product_application' => $request->product_application,
                'display_duration' => $request->display_duration,
                'name' => $request->name,
                'active' => ($request->active == 'false') ? 0 : 1
            ];

            $advertisement->update($data);
            $advertisement->saveMaterials(json_decode($request->materials), $request->file('files'));

            return $this->response($advertisement, 'Successfully Modified!', 200);
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
            $advertisement = Advertisement::find($id);
            $advertisement->delete();
            return $this->response($advertisement, 'Successfully Deleted!', 200);
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

    public function getAllType(Request $request)
    {
        try
        {
            $advertisements = ContentMaterialViewModel::when(request('search'), function($query){
                return $query->where('advertisements.name', 'LIKE', '%' . request('search') . '%')
                             ->where('companies.name', 'LIKE', '%' . request('search') . '%')
                             ->where('brands.name', 'LIKE', '%' . request('search') . '%');
            })
            ->where('advertisements.status_id', 5)
            ->join('advertisements', 'advertisement_materials.advertisement_id', '=', 'advertisements.id')
            ->leftJoin('companies', 'advertisements.company_id', '=', 'companies.id')
            ->leftJoin('brands', 'advertisements.brand_id', '=', 'brands.id')
            ->select('advertisement_materials.*')
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($advertisements, 'Successfully Retreived!', 200);
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

    public function getMaterialDetails($id)
    {
        try
        {
            $material = ContentMaterialViewModel::find($id);
            return $this->response($material, 'Successfully Retreived!', 200);
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

    public function deleteMaterial($id) {
        try
    	{
            $screens = AdvertisementScreen::where('material_id', $id)->count();
            if($screens > 0) {
                return response([
                    'message' => "You can't this delete material, it's been assigned to a screen/s. ",
                    'status' => false,
                    'status_code' => 422,
                ], 422);
            }
            
            $material = AdvertisementMaterial::find($id);
            $material->delete();

            return $this->response($material, 'Successfully Deleted!.', 200);
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
