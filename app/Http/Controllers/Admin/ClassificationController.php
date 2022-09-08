<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ClassificationControllerInterface;
use Illuminate\Http\Request;

use App\Models\Classification;
use App\Models\ViewModels\AdminViewModel;

class ClassificationController extends AppBaseController implements ClassificationControllerInterface
{
    /********************************************
    * 			CLASSIFICATION MANAGEMENT		*
    *********************************************/
    public function __construct()
    {
        $this->module_id = 9; 
        $this->module_name = 'Classifications';
    }

    public function index()
    {
        return view('admin.classifications');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $classifications = Classification::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($classifications, 'Successfully Retreived!', 200);
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
            $classification = Classification::find($id);
            return $this->response($classification, 'Successfully Retreived!', 200);
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

            $classification = Classification::create($data);

            return $this->response($classification, 'Successfully Created!', 200);
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
            $classification = Classification::find($request->id);

            $data = [
                'name' => $request->name,
                'active' => $request->active
            ];

            $classification->update($data);

            return $this->response($classification, 'Successfully Modified!', 200);
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
            $classification = Classification::find($id);
            $classification->delete();
            return $this->response($classification, 'Successfully Deleted!', 200);
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
