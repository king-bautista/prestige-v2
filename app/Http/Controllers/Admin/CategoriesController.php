<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CategoriesControllerInterface;
use Illuminate\Http\Request;

use App\Models\Category;
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
            $kiosk_image_primary_path = null;
            $kiosk_image_top_path = null;
            $online_image_primary_path = null;
            $online_image_top_path = null;

            $kiosk_image_primary = $request->file('kiosk_image_primary');
            if($kiosk_image_primary) {
                $originalname = $kiosk_image_primary->getClientOriginalName();
                $kiosk_image_primary_path = $kiosk_image_primary->move('uploads/media/category/', str_replace(' ','-', $originalname)); 
            }

            $kiosk_image_top = $request->file('kiosk_image_top');
            if($kiosk_image_top) {
                $originalname = $kiosk_image_top->getClientOriginalName();
                $kiosk_image_top_path = $kiosk_image_top->move('uploads/media/category/strips/', str_replace(' ','-', $originalname)); 
            }

            $online_image_primary = $request->file('online_image_primary');
            if($online_image_primary) {
                $originalname = $online_image_primary->getClientOriginalName();
                $online_image_primary_path = $online_image_primary->move('uploads/media/category/', str_replace(' ','-', $originalname)); 
            }

            $online_image_top = $request->file('online_image_top');
            if($online_image_top) {
                $originalname = $online_image_top->getClientOriginalName();
                $online_image_top_path = $online_image_top->move('uploads/media/category/strips/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'parent_id' => ($request->parent_id == 'null') ? 0 : $request->parent_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'kiosk_image_primary' => str_replace('\\', '/', $kiosk_image_primary_path),
                'kiosk_image_top' => str_replace('\\', '/', $kiosk_image_top_path),
                'online_image_primary' => str_replace('\\', '/', $online_image_primary_path),
                'online_image_top' => str_replace('\\', '/', $online_image_top_path),
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

            $kiosk_image_primary_path = '';
            $kiosk_image_top_path = '';
            $online_image_primary_path = '';
            $online_image_top_path = '';

            $kiosk_image_primary = $request->file('kiosk_image_primary');
            if($kiosk_image_primary) {
                $originalname = $kiosk_image_primary->getClientOriginalName();
                $kiosk_image_primary_path = $kiosk_image_primary->move('uploads/media/category/', str_replace(' ','-', $originalname)); 
            }

            $kiosk_image_top = $request->file('kiosk_image_top');
            if($kiosk_image_top) {
                $originalname = $kiosk_image_top->getClientOriginalName();
                $kiosk_image_top_path = $kiosk_image_top->move('uploads/media/category/strips/', str_replace(' ','-', $originalname)); 
            }

            $online_image_primary = $request->file('online_image_primary');
            if($online_image_primary) {
                $originalname = $online_image_primary->getClientOriginalName();
                $online_image_primary_path = $online_image_primary->move('uploads/media/category/', str_replace(' ','-', $originalname)); 
            }

            $online_image_top = $request->file('online_image_top');
            if($online_image_top) {
                $originalname = $online_image_top->getClientOriginalName();
                $online_image_top_path = $online_image_top->move('uploads/media/category/strips/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'parent_id' => ($request->parent_id == 'null') ? 0 : $request->parent_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'kiosk_image_primary' => ($kiosk_image_primary_path) ? str_replace('\\', '/', $kiosk_image_primary_path) : $category->kiosk_image_primary,
                'kiosk_image_top' => ($kiosk_image_top_path) ? str_replace('\\', '/', $kiosk_image_top_path) : $category->kiosk_image_top,
                'online_image_primary' => ($online_image_primary_path) ? str_replace('\\', '/', $online_image_primary_path) : $category->online_image_primary,
                'online_image_top' => ($online_image_top_path) ? str_replace('\\', '/', $online_image_top_path) : $category->online_image_top,
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
            $categories = CategoryViewModel::where('parent_id', 0)->get();
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

    public function deleteImage(Request $request)
    {
        try
        {
            $category = Category::find($request->id);
            $category->update([$request->column => null]);
            return $this->response($category, 'Successfully modified!', 200);
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
