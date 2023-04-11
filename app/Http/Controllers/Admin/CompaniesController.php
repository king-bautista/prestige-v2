<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CompaniesControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;

use App\Models\Company;
use App\Models\ViewModels\CompanyViewModel;
use App\Models\ViewModels\AdminViewModel;
use App\Exports\Export;
use Storage;

class CompaniesController extends AppBaseController implements CompaniesControllerInterface
{
    /****************************************
    * 			COMPANIES MANAGEMENT	 	*
    ****************************************/
    public function __construct()
    {
        $this->module_id = 33; 
        $this->module_name = 'Companies';
    }

    public function index()
    {
        return view('admin.companies');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $companies = CompanyViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('address', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('tin', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($companies, 'Successfully Retreived!', 200);
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
            $company = CompanyViewModel::find($id);
            return $this->response($company, 'Successfully Retreived!', 200);
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

    public function store(CompanyRequest $request)
    {
        try
    	{
            $data = [
                'parent_id' => ($request->parent_id == 'null') ? 0 : $request->parent_id,
                'classification_id' => $request->classification_id,
                'name' => $request->name,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'address' => $request->address,
                'tin' => $request->tin,
                'active' => 1
            ];

            $company = Company::create($data);
            $company->saveBrands($request->brands);

            return $this->response($company, 'Successfully Created!', 200);
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

    public function update(CompanyRequest $request)
    {
        try
    	{
            $company = Company::find($request->id);

            $data = [
                'parent_id' => ($request->parent_id == 'null') ? 0 : $request->parent_id,
                'classification_id' => $request->classification_id,
                'name' => $request->name,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'address' => $request->address,
                'tin' => $request->tin,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $company->update($data);
            $company->saveBrands($request->brands);

            return $this->response($company, 'Successfully Modified!', 200);
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
            $company = Company::find($id);
            $company->delete();
            return $this->response($company, 'Successfully Deleted!', 200);
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

    public function getAll()
    {
        try
        {
            $companies = CompanyViewModel::get();
            return $this->response($companies, 'Successfully Retreived!', 200);
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
            $companies = CompanyViewModel::whereNull('parent_id')->where('active', 1)->get();
            return $this->response($companies, 'Successfully Retreived!', 200);
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

            $company_management = CompanyViewModel::get();
            $reports = [];
            foreach ($company_management as $company) {
                $reports[] = [
                    'name' => $company->,
                    'parent_company' => $company->,
                    'classification_name' => $company->,
                    'email' => $company->,
                    'contact_number' => $company->,
                    'address' => $company->,
                    'tin_number' => $company->,
                    'status' => ($company->active == 1) ? 'Active' : 'Inactive',
                    'updated_at' => $company->updated_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "site_management.csv";
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
