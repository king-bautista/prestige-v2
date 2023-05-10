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

    public function banner()
    {
        return view('admin.advertisements_banner');
    }

    public function fullscreen()
    {
        return view('admin.advertisements_fullscreen');
    }

    public function popups()
    {
        return view('admin.advertisements_popups');
    }

    public function events()
    {
        return view('admin.advertisements_events');
    }

    public function promos()
    {
        return view('admin.advertisements_promos');
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

    public function validateMaterial(Request $request)
    {
        try
    	{
            $file_path = $request->file('file_path');
            $extension = null;
            $file_path_path = null;
            $file_size = null;
            $dimension = null;
            $width = null;
            $height = null;

            if($file_path) {
                $originalname = $file_path->getClientOriginalName();
                $extension = $file_path->getClientOriginalExtension();
                $mime_type = explode("/",$file_path->getClientMimeType());
                $file_size = $file_path->getSize();
                $file_path_path = $file_path->move('uploads/media/advertisements/'.strtolower($request->ad_type).'/', str_replace(' ','-', $originalname));
                $file_type = $mime_type[0];
                if($file_type == 'image') {
                    $image_size = getimagesize($file_path_path);
                    $width = $image_size[0];
                    $height = $image_size[1];
                    $dimension = $width.' x '.$height;
                }
            }
            
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
        // try
    	// {
            // $banner_portrait = $request->file('banner_portrait');
            // if($banner_portrait) {

            // }

            // $file_path = $request->file('file_path');
            // $extension = null;
            // $file_path_path = null;
            // $file_size = null;
            // $dimension = null;
            // $width = null;
            // $height = null;

            // if($file_path) {
            //     $originalname = $file_path->getClientOriginalName();
            //     $extension = $file_path->getClientOriginalExtension();
            //     $mime_type = explode("/",$file_path->getClientMimeType());
            //     $file_size = $file_path->getSize();
            //     $file_path_path = $file_path->move('uploads/media/advertisements/'.strtolower($request->ad_type).'/', str_replace(' ','-', $originalname));
            //     $file_type = $mime_type[0];
            //     if($file_type == 'image') {
            //         $image_size = getimagesize($file_path_path);
            //         $width = $image_size[0];
            //         $height = $image_size[1];
            //         $dimension = $width.' x '.$height;
            //     }
            // }

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
            $advertisement->saveScreens($screen_ids);
            //$advertisement->saveMaterials($requests);

            return $this->response($advertisement, 'Successfully Created!', 200);
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

    public function update(AdvertisementRequest $request)
    {
        // try
    	// {
            $advertisement = Advertisement::find($request->id);
            $advertisement->touch();

            $company_id = json_decode($request->company_id);
            $contract_id = json_decode($request->contract_id);
            $brand_id = json_decode($request->brand_id);
            $status_id = json_decode($request->status_id);
            $screen_ids = json_decode($request->screen_ids);

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
            $advertisement->saveScreens($screen_ids);
            //$advertisement->saveMaterials($requests);

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
