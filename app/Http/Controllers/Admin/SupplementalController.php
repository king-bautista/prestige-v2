<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\SupplementalControllerInterface;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\CategoryViewModel;

class SupplementalController extends AppBaseController implements SupplementalControllerInterface
{
    public function __construct()
    {
        $this->module_id = 8; 
        $this->module_name = 'Supplementals';
    }

    public function index()
    {
        return view('admin.supplementals');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $categories = CategoryViewModel::where('category_type', 2)
            ->when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                             ->where('descriptions', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($categories, 'Successfully Retreived!', 200);
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
            $category = CategoryViewModel::find($id);
            return $this->response($category, 'Successfully Retreived!', 200);
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
                'parent_id' => ($request->parent_id == 'null') ? 0 : $request->parent_id,
                'supplemental_category_id' => ($request->category_id == 'null') ? 0 : $request->category_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'class_name' => $request->class_name,
                'category_type' => $request->category_type,
                'active' => 1,
            ];

            $category = Category::create($data);

            return $this->response($category, 'Successfully Created!', 200);
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
            $category = Category::find($request->id);

            $data = [
                'parent_id' => ($request->parent_id == 'null') ? 0 : $request->parent_id,
                'supplemental_category_id' => ($request->category_id == 'null') ? 0 : $request->category_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'class_name' => $request->class_name,
                'category_type' => $request->category_type,
                'active' => ($request->active == false) ? 0 : 1,
            ];

            $category->update($data);

            return $this->response($category, 'Successfully Modified!', 200);
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
            $category = Category::find($id);
            $category->delete();
            return $this->response($category, 'Successfully Deleted!', 200);
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

    public function getParent()
    {
        try
        {
            $supplementals = CategoryViewModel::whereNull('parent_id')->where('category_type', 2)->get();
            return $this->response($supplementals, 'Successfully Retreived!', 200);
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

    public function getChild()
    {
        try
        {
            $supplementals = CategoryViewModel::whereNotNull('parent_id')->where('category_type', 2)->get();
            return $this->response($supplementals, 'Successfully Retreived!', 200);
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
