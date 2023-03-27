<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\AmenitiesControllerInterface;
use Illuminate\Http\Request;

use App\Models\Amenity;
use App\Models\ViewModels\AdminViewModel;

class AmenitiesController extends AppBaseController implements AmenitiesControllerInterface
{
    /****************************************
    * 			AMENITIES MANAGEMENT		*
    ****************************************/
    public function __construct()
    {
        $this->module_id = 31; 
        $this->module_name = 'Amenities';
    }

    public function index()
    {
        return view('admin.amenities');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $amenitiess = Amenity::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($amenitiess, 'Successfully Retreived!', 200);
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
            $amenities = Amenity::find($id);
            return $this->response($amenities, 'Successfully Retreived!', 200);
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
            $icon = $request->file('icon');
            $icon_path = '';
            if($icon) {
                $originalname = $icon->getClientOriginalName();
                $icon_path = $icon->move('uploads/media/services/', str_replace(' ','-', $originalname)); 
            }
            
            $data = [
                'name' => $request->name,
                'icon' => str_replace('\\', '/', $icon_path),
                'active' => 1
            ];

            $amenities = Amenity::create($data);

            return $this->response($amenities, 'Successfully Created!', 200);
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
            $amenities = Amenity::find($request->id);

            $icon = $request->file('icon');
            $icon_path = '';
            if($icon) {
                $originalname = $icon->getClientOriginalName();
                $icon_path = $icon->move('uploads/media/services/', str_replace(' ','-', $originalname)); 
            }  

            $data = [
                'name' => $request->name,
                'icon' => ($icon_path) ? str_replace('\\', '/', $icon_path) : $amenities->icon,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $amenities->update($data);

            return $this->response($amenities, 'Successfully Modified!', 200);
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
            $amenities = Amenity::find($id);
            $amenities->delete();
            return $this->response($amenities, 'Successfully Deleted!', 200);
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
