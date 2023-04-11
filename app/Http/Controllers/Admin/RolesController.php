<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\RolesControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;

use App\Models\Role;
use App\Models\ViewModels\ModuleViewModel;
use App\Models\ViewModels\RoleViewModel;
use App\Models\ViewModels\AdminViewModel;

use App\Exports\Export;
use Storage;

class RolesController extends AppBaseController implements RolesControllerInterface
{
    /************************************
    * 			ROLES MANAGEMENT		*
    ************************************/
    public function __construct()
    {
        $this->module_id = 3; 
        $this->module_name = 'Roles';
    }

    public function index()
    {
        return view('admin.roles');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $roles = Role::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('description', 'LIKE', '%' . request('search') . '%');
            })
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

    public function store(RoleRequest $request)
    {
        try
    	{
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
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

    public function update(RoleRequest $request)
    {
        try
    	{
            $role = Role::find($request->id);

            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
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
            $role = Role::find($id);
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

    public function getModules(Request $request)
    {
        try
    	{            
            $modules = ModuleViewModel::whereNull('parent_id')->where('role', $request->type)->get();
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

    public function getAdmin()
    {
        try
    	{
            $roles = Role::where('type', 'Admin')->get();
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

    public function getPortal()
    {
        try
    	{
            $roles = Role::where('type', 'Portal')->get();
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

    public function downloadCsv()
    {
        try {

            $role_management = RoleViewModel::get();
            $reports = [];
            foreach ($role_management as $role) {
                $reports[] = [
                    'name' => $role->name,
                    'descirption' => $role->description,
                    'type' => $role->type,
                    'status' => ($role->active == 1) ? 'Active' : 'Inactive',
                    'updated_at' => $role->updated_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "role.csv";
            // Store on default disk
            Excel::store(new Export($reports), $directory . $filename);

            $data = [
                'filepath' => '/storage/export/reports/' . $filename,
                'filename' => $filename
            ];

            if (Storage::exists($directory . $filename))
                return $this->response($data, 'Successfully Retreived!', 200);

            return $this->response(false, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

}
