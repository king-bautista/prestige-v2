<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\SupplementalControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Imports\SupplementalsImport;
use App\Exports\Export;
use Storage;

use App\Models\Category;
use App\Models\AdminViewModels\CategoryViewModel;

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
        try {
            $categories = CategoryViewModel::where('category_type', 2)
                ->when(request('search'), function ($query) {
                    return $query->where('name', 'LIKE', '%' . request('search') . '%')
                        ->where('descriptions', 'LIKE', '%' . request('search') . '%');
                })
                ->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($categories, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function details($id)
    {
        try {
            $category = CategoryViewModel::find($id);
            return $this->response($category, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(CategoryRequest $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(CategoryRequest $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::find($id);
            $category->delete();
            return $this->response($category, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getParent()
    {
        try {
            $supplementals = CategoryViewModel::whereNull('parent_id')->where('category_type', 2)->get();
            return $this->response($supplementals, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getChild()
    {
        try {
            $supplementals = CategoryViewModel::whereNotNull('parent_id')->where('category_type', 2)->get();
            return $this->response($supplementals, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function batchUpload(Request $request)
    {
        try {
            Excel::import(new SupplementalsImport, $request->file('file'));
            return $this->response(true, 'Successfully Uploaded!', 200);
        } catch (\Exception $e) {
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

            $supplemental_management = CategoryViewModel::where('category_type', 2)->get();
            $reports = [];
            foreach ($supplemental_management as $supplemental) {
                $reports[] = [
                    'id' => $supplemental->id,
                    'parent_id' => $supplemental->parent_id,
                    'supplemental_category_id' => $supplemental->supplemental_category_id,
                    'name' => $supplemental->name,
                    'description' => $supplemental->descriptions,
                    'category_type' => $supplemental->category_type,
                    'active' => $supplemental->active,
                    'created_at' => $supplemental->created_at,
                    'updated_at' => $supplemental->updated_at,
                    'deleted_at' => $supplemental->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "supplemental-management.csv";
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

    public function downloadCsvtemplate()
    {
        try {
            $reports[] = [
                'id' => '',
                'parent_id' => '',
                'supplemental_category_id' => '',
                'name' => '',
                'description' => '',
                'category_type' => '',
                'active' => '',
                'created_at' => '',
                'updated_at' => '',
                'deleted_at' => '',
            ];

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "supplemental-management-template.csv";
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
