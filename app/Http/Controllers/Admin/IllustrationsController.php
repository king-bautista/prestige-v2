<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\IllustrationsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Imports\IllustrationsImport;

use App\Models\CompanyCategory;
use App\Models\AdminViewModels\CompanyCategoryViewModel;
use App\Exports\Export;
use Storage;
use URL;


class IllustrationsController extends AppBaseController implements IllustrationsControllerInterface
{
    /****************************************
     * 			ILLUSTRATION MANAGEMENT	 	*
     ****************************************/
    public function __construct()
    {
        $this->module_id = 34;
        $this->module_name = 'illustration';
    }

    public function index()
    {
        return view('admin.illustrations');
    }

    public function list(Request $request)
    {
        try {
            $company_categories = CompanyCategoryViewModel::when(request('search'), function ($query) {
                return $query->where('label', 'LIKE', request('search') . '%')
                    ->orWhere('c1.name', 'LIKE', request('search') . '%')
                    ->orWhere('c2.name', 'LIKE', request('search') . '%')
                    ->orWhere('label', 'LIKE', '%' . request('search'))
                    ->orWhere('c1.name', 'LIKE', '%' . request('search'))
                    ->orWhere('c2.name', 'LIKE', '%' . request('search'));
            })
            ->leftJoin('categories as c1', 'company_categories.category_id', '=', 'c1.id')
            ->leftJoin('categories as c2', 'company_categories.sub_category_id', '=', 'c2.id')
            ->select('company_categories.*')
            ->orderBy('company_categories.updated_at', 'DESC')
            ->paginate(request('perPage'));

            return $this->responsePaginate($company_categories, 'Successfully Retreived!', 200);
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
            $company_category = CompanyCategoryViewModel::find($id);
            return $this->response($company_category, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(Request $request)
    {
        try {
            $kiosk_image_primary_path = null;
            $kiosk_image_top_path = null;
            $date = date('YmdHis');

            $kiosk_image_primary = $request->file('kiosk_image_primary');
            if ($kiosk_image_primary) {
                $originalname = $kiosk_image_primary->getClientOriginalName();
                $kiosk_image_primary_path = $kiosk_image_primary->move('uploads/media/category/', $date . str_replace(' ', '-', $originalname));
            }

            $kiosk_image_top = $request->file('kiosk_image_top');
            if ($kiosk_image_top) {
                $originalname = $kiosk_image_top->getClientOriginalName();
                $kiosk_image_top_path = $kiosk_image_top->move('uploads/media/category/strips/', $date . str_replace(' ', '-', $originalname));
            }

            $data = [
                'company_id' => ($request->company_id == 'null') ? 0 : $request->company_id,
                'category_id' => ($request->category_id == 'null') ? 0 : $request->category_id,
                'sub_category_id' => ($request->sub_category_id == 'null') ? 0 : $request->sub_category_id,
                'site_id' => ($request->site_id == 'null') ? 0 : $request->site_id,
                'label' => ($request->label) ? $request->label : null,
                'kiosk_image_primary' => str_replace('\\', '/', $kiosk_image_primary_path),
                'kiosk_image_top' => str_replace('\\', '/', $kiosk_image_top_path),
                'active' => 1,
            ];

            $company_category = CompanyCategory::create($data);

            return $this->response($company_category, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(Request $request)
    {
        try {
            $company_category = CompanyCategory::find($request->id);

            $kiosk_image_primary_path = '';
            $kiosk_image_top_path = '';
            $date = date('YmdHis');

            $kiosk_image_primary = $request->file('kiosk_image_primary');
            if ($kiosk_image_primary) {
                $originalname = $kiosk_image_primary->getClientOriginalName();
                $kiosk_image_primary_path = $kiosk_image_primary->move('uploads/media/category/', $date . str_replace(' ', '-', $originalname));
            }

            $kiosk_image_top = $request->file('kiosk_image_top');
            if ($kiosk_image_top) {
                $originalname = $kiosk_image_top->getClientOriginalName();
                $kiosk_image_top_path = $kiosk_image_top->move('uploads/media/category/strips/', $date . str_replace(' ', '-', $originalname));
            }

            $data = [
                'company_id' => ($request->company_id == 'null') ? 0 : $request->company_id,
                'category_id' => ($request->category_id == 'null') ? 0 : $request->category_id,
                'sub_category_id' => ($request->sub_category_id == 'null') ? 0 : $request->sub_category_id,
                'site_id' => ($request->site_id == 'null') ? 0 : $request->site_id,
                'label' => ($request->label) ? $request->label : null,
                'kiosk_image_primary' => ($kiosk_image_primary_path) ? str_replace('\\', '/', $kiosk_image_primary_path) : $company_category->kiosk_image_primary,
                'kiosk_image_top' => ($kiosk_image_top_path) ? str_replace('\\', '/', $kiosk_image_top_path) : $company_category->kiosk_image_top,
                'active' => $this->checkBolean($request->active),
            ];

            $company_category->update($data);

            return $this->response($company_category, 'Successfully Modified!', 200);
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
            $company_category = CompanyCategory::find($id);
            $company_category->delete();
            return $this->response($company_category, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    Public function batchUpload(Request $request)
    { 
        try
        {
            Excel::import(new IllustrationsImport, $request->file('file'));
            return $this->response(true, 'Successfully Uploaded!', 200);  
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

            $illustration_management =  CompanyCategoryViewModel::get();
            $reports = [];
            foreach ($illustration_management as $illustration) {
                $reports[] = [
                    'id' => $illustration->id,
                    'company_id' => $illustration->company_id,
                    'company_name' => $illustration->company_name,
                    'category_id' => $illustration->category_id,
                    'category_name' => $illustration->category_name,
                    'sub_category_id' => $illustration->sub_category_id,
                    'site_id' => $illustration->site_id,
                    'site_name' => $illustration->site_name,
                    'label' => $illustration->label,
                    'kiosk_image_primary' => $illustration->kiosk_image_primary_path,
                    'kiosk_image_top' => $illustration->kiosk_image_top_path,
                    'online_image_primary' => $illustration->online_image_primary,
                    'online_image_top' => $illustration->online_image_top,
                    'mobile_image_primary' => $illustration->mobile_image_primary,
                    'mobile_image_top' => $illustration->mobile_image_top,
                    'active' => $illustration->active,
                    'created_at' => $illustration->created_at,
                    'updated_at' => $illustration->updated_at,
                    'deleted_at' => $illustration->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "site-categories-illustration.csv";
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

    public function downloadCsvTemplate()
    {
        try {
            $reports[] = [
                'id' => '',
                'company_id' => '',
                'company_name' => '',
                'category_id' => '',
                'category_name' => '',
                'sub_category_id' => '',
                'site_id' => '',
                'site_name' => '',
                'label' => '',
                'kiosk_image_primary' => '',
                'kiosk_image_top' => '',
                'online_image_primary' => '',
                'online_image_top' => '',
                'mobile_image_primary' => '',
                'mobile_image_top' => '',
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

            $filename = "site-categories-illustration-template.csv";
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
