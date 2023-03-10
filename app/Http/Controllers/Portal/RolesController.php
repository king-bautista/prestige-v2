<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\RolesControllerInterface;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\ViewModels\ModuleViewModel;
use App\Models\ViewModels\RoleViewModel;
use App\Models\ViewModels\UserViewModel;

class RolesController extends AppBaseController implements RolesControllerInterface
{
    /************************************
    * 			ROLES MANAGEMENT		*
    ************************************/
    public function __construct()
    {
        $this->module_id = 47; 
        $this->module_name = 'Roles';
    }

    public function index()
    {
        return view('portal.roles');
    }

    public function list(Request $request)
    {  
        try
        {
            $user = UserViewModel::find(Auth::guard('portal')->user()->id);
            $company_id = ($user->company_id) ? $user->company_id : 0;
    
            $roles = RoleViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('description', 'LIKE', '%' . request('search') . '%');
            })
            ->where('company_id', $company_id)
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($roles, 'Successfully Retreived!', 200);
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
            $role = RoleViewModel::find($id);
            return $this->response($role, 'Successfully Retreived!', 200);
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
            $user = UserViewModel::find(Auth::guard('portal')->user()->id);
            $company_id = ($user->company_id) ? $user->company_id : 0;

            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'company_id' => $company_id,
                'active' => 1
            ];

            $role = Role::create($data);

            return $this->response($role, 'Successfully Created!', 200);
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
            $role = Role::find($request->id);

            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'active' => $request->isActive
            ];

            $role->update($data);
            $role->setPermissions($request->permissions);

            return $this->response($role, 'Successfully Modified!', 200);
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
            $role = UserRole::find($id);
            $role->delete();
            return $this->response($role, 'Successfully Deleted!', 200);
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

    public function getModules()
    {
        try
    	{
            $modules = ModuleViewModel::whereNull('parent_id')->get();
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

    public function getAll()
    {
        try
    	{
            $roles = UserRole::get();
            return $this->response($roles, 'Successfully Retreived!', 200);
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
