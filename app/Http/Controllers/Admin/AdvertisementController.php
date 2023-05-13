<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\AdvertisementControllerInterface;

use Illuminate\Http\Request;
use App\Http\Requests\AdvertisementRequest;

use App\Models\Advertisement;
use App\Models\ViewModels\AdvertisementViewModel;
use App\Models\ViewModels\AdminViewModel;



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
        // try
    	// {
            // dd($request->file('files'));

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
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
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

}
