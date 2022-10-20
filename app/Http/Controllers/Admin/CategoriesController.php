<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CategoriesControllerInterface;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\CategoryLabel;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\CategoryViewModel;

class CategoriesController extends AppBaseController implements CategoriesControllerInterface
{
    /****************************************
    * 			CATEGORIES MANAGEMENT		*
    ****************************************/
    public function __construct()
    {
        $this->module_id = 7; 
        $this->module_name = 'Categories';
    }

    public function index()
    {
        return view('admin.categories');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $categories = CategoryViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%');
            })
            ->where('category_type', 1)
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
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'class_name' => $request->class_name,
                'category_type' => $request->category_type,
                'active' => ($request->active == 'false') ? 0 : 1,
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

    public function getAllCategories()
    {
        try
        {
            $categories = CategoryViewModel::whereNull('parent_id')->where('category_type', 1)->get();
            return $this->response($categories, 'Successfully Retreived!', 200);
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

    public function getAll($id = 0)
    {
        try
        {
            $categories = CategoryViewModel::when($id, function($query) use ($id){
                return $query->where('parent_id', $id);
            })
            ->when(!$id, function($query){
                return $query->whereNull('parent_id');
            })
            ->get();
            return $this->response($categories, 'Successfully Retreived!', 200);
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

    public function getLabels($id)
    {
        try
        {
            $labels = CategoryLabel::where('category_id', $id)->get();
            return $this->response($labels, 'Successfully Retreived!', 200);
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

    public function saveLabels(Request $request)
    {
        try
        {
            $category_label = CategoryLabel::updateOrCreate(
                [
                   'category_id' => $request->category_id,
                   'site_id' => $request->site_id
                ],
                [
                   'name' => $request->label
                ],
            );

            return $this->response($category_label, 'Successfully saved!', 200);
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

    public function deleteLabel($id)
    {
        try
    	{
            $label = CategoryLabel::find($id);
            $label->delete();
            return $this->response($label, 'Successfully Deleted!', 200);
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
