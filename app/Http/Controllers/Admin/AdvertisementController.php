<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\AdvertisementControllerInterface;

use Illuminate\Http\Request;

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

    public function list($ad_type, Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
            $advertisements = AdvertisementViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->where('ad_type', $ad_type)
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

    public function store(Request $request)
    {
        try
    	{
            $company_id = json_decode($request->company_id);
            $brand_id = json_decode($request->brand_id);

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

            $data = [
                'company_id' => ($company_id) ? $company_id->id : null,
                'brand_id' => ($brand_id) ? $brand_id->id : null,
                'ad_type' => $request->ad_type,
                'name' => $request->name,
                'file_path' => str_replace('\\', '/', $file_path_path),
                'file_type' => $file_type,
                'file_size' => $file_size,
                'dimension' => $dimension,
                'width' => $width,
                'height' => $height,
                'display_duration' => $request->display_duration,
                'active' => ($request->active == 'false') ? 0 : 1,
                'status_id' => 5,
            ];

            $advertisement = Advertisement::create($data);
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

    public function update(Request $request)
    {
        try
    	{
            $advertisement = Advertisement::find($request->id);
            $advertisement->touch();

            $company_id = json_decode($request->company_id);
            $brand_id = json_decode($request->brand_id);

            $file_path = $request->file('file_path');
            $extension = null;
            $file_path_path = null;
            $file_size = null;
            $dimension = null;
            $width = null;
            $height = null;
            $file_type = null;

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

            $data = [
                'company_id' => ($company_id) ? $company_id->id : $advertisement->brand_id,
                'brand_id' => ($brand_id) ? $brand_id->id : $advertisement->brand_id,
                'ad_type' => $request->ad_type,
                'name' => $request->name,
                'file_path' => ($file_path_path) ? str_replace('\\', '/', $file_path_path) : $advertisement->file_path,
                'file_type' => ($file_type) ? $file_type : $advertisement->file_type,
                'file_size' => ($file_size) ? $file_size : $advertisement->file_size,
                'dimension' => ($dimension) ? $dimension : $advertisement->dimension,
                'width' => ($width) ? $width : $advertisement->width,
                'height' => ($height) ? $height : $advertisement->height,
                'display_duration' => $request->display_duration,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $advertisement->update($data);
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
