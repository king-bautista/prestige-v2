<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\IllustrationsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Models\CompanyCategory;
use App\Models\ViewModels\CompanyCategoryViewModel;
use App\Models\ViewModels\AdminViewModel;
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
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $company_categories = CompanyCategoryViewModel::when(request('search'), function ($query) {
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('address', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('tin', 'LIKE', '%' . request('search') . '%');
            })
                ->latest()
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
                'kiosk_image_primary' => ($kiosk_image_primary_path) ? str_replace('\\', '/', $kiosk_image_primary_path) : $company_category->kiosk_image_primary,
                'kiosk_image_top' => ($kiosk_image_top_path) ? str_replace('\\', '/', $kiosk_image_top_path) : $company_category->kiosk_image_top,
                'active' => ($request->active == 'false') ? 0 : 1,
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

    public function downloadCsv()
    {
        try {

            $illustration_management =  CompanyCategoryViewModel::get();
            $reports = [];
            foreach ($illustration_management as $illustration) {
                $reports[] = [
                    //'kiosk_image_primary_path' => ($illustration->kiosk_image_primary_path != "") ? URL::to("/" . $illustration->kiosk_image_primary_path) : " ",
                    //'kiosk_image_top_path' => ($illustration->kiosk_image_top_path != "") ? URL::to("/" . $illustration->kiosk_image_top_path) : " ",
                    //'company_name' => $illustration->company_name,
                    //'category_name' => $illustration->category_name,
                    //'sub_category_name' => $illustration->sub_category_name,
                    //'site_name' => $illustration->site_name,
                    'status' => ($illustration->active == 1) ? 'Active' : 'Inactive',
                    'updated_at' => $illustration->updated_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "illustration_management.csv";
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
