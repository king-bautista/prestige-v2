<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\AmenitiesControllerInterface;
use Illuminate\Http\Request;

use App\Models\Amenities;
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

            $amenitiess = Amenities::when(request('search'), function($query){
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
                'status_code' => 401,
            ], 401);
        }
    }

    public function details($id)
    {
        try
        {
            $amenities = Amenities::find($id);
            return $this->response($amenities, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function store(Request $request)
    {
        try
    	{
            $data = [
                'name' => $request->name,
                'active' => 1
            ];

            $amenities = Amenities::create($data);

            return $this->response($amenities, 'Successfully Created!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function update(Request $request)
    {
        try
    	{
            $amenities = Amenities::find($request->id);

            $data = [
                'name' => $request->name,
                'active' => $request->active
            ];

            $amenities->update($data);

            return $this->response($amenities, 'Successfully Modified!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function delete($id)
    {
        try
    	{
            $amenities = Amenities::find($id);
            $amenities->delete();
            return $this->response($amenities, 'Successfully Deleted!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }
}
