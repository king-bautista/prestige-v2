<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\AmenitiesControllerInterface;
use Illuminate\Http\Request;

use App\Models\Amenity;
use App\Models\AdminViewModels\UserViewModel;


class AmenitiesController extends AppBaseController implements AmenitiesControllerInterface
{
    /****************************************
    * 			AMENITIES MANAGEMENT		*
    ****************************************/
    public function __construct()
    {
        $this->module_id = 54; 
        $this->module_name = 'Amenities';
    }

    public function index()
    {
        return view('portal.amenities');
    }

    public function list(Request $request)
    {
        try
        {
            $id = Auth::guard('portal')->user()->id;
            $user = UserViewModel::find($id);
            $site_ids = $user->getSiteIds();

            $amenitiess = Amenity::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->whereIn('site_id', $site_ids)
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
            $data = [
                'name' => $request->name,
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
