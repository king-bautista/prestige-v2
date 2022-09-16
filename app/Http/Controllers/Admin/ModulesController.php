<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ModulesControllerInterface;
use Illuminate\Http\Request;

use App\Models\Module;
use App\Models\ViewModels\ModuleViewModel;
use App\Models\ViewModels\AdminViewModel;

class ModulesController extends AppBaseController implements ModulesControllerInterface
{
    /************************************
    * 			MODULE MANAGEMENT		*
    ************************************/
    public function __construct()
    {
        $this->module_id = 4; 
        $this->module_name = 'Modules';
    }

    public function index()
    {
        return view('admin.modules');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $modules = ModuleViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('link', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('class_name', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($modules, 'Successfully Retreived!', 200);
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
            $module = Module::find($id);
            return $this->response($module, 'Successfully Retreived!', 200);
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
                'parent_id' => $request->parent_id,
                'link' => $request->link,
                'class_name' => $request->class_name,
                'active' => 1
            ];

            $module = Module::create($data);

            return $this->response($module, 'Successfully Created!', 200);
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
            $module = Module::find($request->id);

            $data = [
                'name' => $request->name,
                'parent_id' => $request->parent_id,
                'link' => $request->link,
                'class_name' => $request->class_name,
                'active' => $request->isActive
            ];

            $module->update($data);

            return $this->response($module, 'Successfully Modified!', 200);
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
            $module = Module::find($id);
            $module->delete();
            return $this->response($module, 'Successfully Deleted!', 200);
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

    public function getAllLinks()
    {
        try
        {
            $modules = Module::whereNull('parent_id')->get();
            return $this->response($modules, 'Successfully Retreived!', 200);
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
