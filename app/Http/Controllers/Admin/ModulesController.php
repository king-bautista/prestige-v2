<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ModulesControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\ModuleRequest;

use App\Models\Module;
use App\Models\ViewModels\ModuleViewModel;
use App\Models\ViewModels\AdminViewModel;

use App\Exports\Export;
use Storage;
use URL;

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
                             ->orWhere('role', 'LIKE', '%' . request('search') . '%')
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

    public function store(ModuleRequest $request)
    {
        try
    	{
            $data = [
                'name' => $request->name,
                'parent_id' => $request->parent_id,
                'link' => $request->link,
                'role' => $request->role,
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

    public function update(ModuleRequest $request)
    {
        try
    	{
            $module = Module::find($request->id);

            $data = [
                'name' => $request->name,
                'parent_id' => $request->parent_id,
                'link' => $request->link,
                'role' => $request->role,
                'class_name' => $request->class_name,
                'active' => $request->isActive
            ];

            $module->update($data);

            return $this->response($module, 'Successfully Modified!!!!', 200);
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

    public function getParent($id)
    {
        try
        {
            $count = Module::where('parent_id', $id)->get()->count();
            return $this->response($count, 'Successfully Retreived!', 200);
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

            $module_management = ModuleViewModel::get();
            $reports = [];
            foreach ($module_management as $module) {
                $reports[] = [
                    'name' => $module->name,
                    'icon_class_name' => $module->class_name,
                    'parent_link' => $module->parent_link,
                    'role' => $module->role,
                    'link' => URL::to("" . $module->link),
                    //'link' => ($module->link != "#") ? URL::to("/" . $module->link) : " ",
                    'status' => ($module->active == 1) ? 'Active' : 'Inactive',
                    'updated_at' => $module->updated_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "module.csv";
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
