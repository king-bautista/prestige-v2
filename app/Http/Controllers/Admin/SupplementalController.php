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
            $categories = CategoryViewModel::where('categories.category_type', 2)
                ->when(request('search'), function ($query) {
                    return $query->Where('categories.name', 'LIKE', '%' . request('search') . '%')
                        ->orWhere('categories.descriptions', 'LIKE', '%' . request('search') . '%')
                        ->orWhere('cp.name', 'LIKE', '%' . request('search') . '%')
                        ->orWhere('cs.name', 'LIKE', '%' . request('search') . '%');
                })
                ->leftJoin('categories as cp', 'categories.parent_id', '=', 'cp.id')
                ->leftJoin('categories as cs', 'categories.supplemental_category_id', '=', 'cs.id')
                ->select('categories.*')
                ->selectRaw('(select cb.name from categories cb where categories.parent_id = cb.id) as parent_supplemental')
                ->selectRaw('(select cc.name from categories cc where categories.supplemental_category_id = cc.id) as category_name')
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('name', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    $column = $this->checkcolumn(request('order'));
                    if ($column == 'parent_category') {
                        $fields = 'parent_supplemental';
                    } else if ($column == 'supplemental_category_name') {
                        $fields = 'category_name';
                    } else {
                        $fields = $column;
                    }
                    return $query->orderBy($fields, request('sort'));
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
                    'descriptions' => $supplemental->descriptions,
                    'class_name' => $supplemental->classname,
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
                'descriptions' => '',
                'class_name' => '',
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
