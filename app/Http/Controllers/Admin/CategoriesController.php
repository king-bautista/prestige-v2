<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CategoriesControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryLabelRequest;
use App\Exports\Export;
use Storage;

use App\Models\Category;
use App\Models\CategoryLabel;
use App\Models\AdminViewModels\CategoryViewModel;

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
            $categories = CategoryViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                             ->where('descriptions', 'LIKE', '%' . request('search') . '%');
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

    public function store(CategoryRequest $request)
    {
        try
    	{
            $data = [
                'parent_id' => ($request->parent_id == 'null') ? 0 : $request->parent_id,
                'name' => $request->name,
                'descriptions' => ($request->descriptions) ? $request->descriptions : '',
                'class_name' => ($request->class_name) ? $request->class_name : '',
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

    public function update(CategoryRequest $request)
    {
        try
    	{
            $category = Category::find($request->id);

            $data = [
                'parent_id' => ($request->parent_id == 'null') ? 0 : $request->parent_id,
                'name' => $request->name,
                'descriptions' => ($request->descriptions) ? $request->descriptions : '',
                'class_name' => ($request->class_name) ? $request->class_name : '',
                'category_type' => $request->category_type,
                'active' => $this->checkBolean($request->active),
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

    public function saveLabels(CategoryLabelRequest $request)
    {
        try
        {
            $category_label = CategoryLabel::updateOrCreate(
                [
                    'company_id' => $request->company_id,
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

    public function downloadCsv()
    {
        try {

            $category_management = CategoryViewModel::get();
            $reports = [];
            foreach ($category_management as $category) {
                $reports[] = [  
                    'name' => $category->name,
                    'description' => $category->descriptions,
                    'parent_category' => $category->parent_category,
                    'status' => ($category->active == 1) ? 'Active' : 'Inactive',
                    'updated_at' => $category->updated_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "category_management.csv";
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
