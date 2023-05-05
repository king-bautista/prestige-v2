<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CompaniesControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\ContractRequest;

use App\Models\Company;
use App\Models\CompanyBrands;
use App\Models\Contract;
use App\Models\ViewModels\CompanyViewModel;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\ContractViewModel;
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

    public function getBrands($company_id)
    {
        try
        {
            $ids = CompanyBrands::where('company_id', $company_id)->get()->pluck('brand_id');
            $brands = BrandViewModel::whereIn('id', $ids)->get();
            return $this->response($brands, 'Successfully Retreived!', 200);
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

    public function storeBrand(Request $request)
    {
        try
    	{
            $company_brand = CompanyBrands::updateOrCreate(
                [
                    'company_id' => $request->company_id,
                    'brand_id' => $request->id
                ],
            );
            return $this->response($company_brand, 'Successfully Created!', 200);
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

    public function deleteBrand($id, $company_id)
    {
        try
    	{
            $company_brand = CompanyBrands::where('brand_id', $id)->where('company_id', $company_id)->delete();
            return $this->response($company_brand, 'Successfully Deleted!', 200);
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

    public function contractDetails($id)
    {
        try
        {
            $contract = ContractViewModel::find($id);
            return $this->response($contract, 'Successfully Retreived!', 200);
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

    public function storeContract(ContractRequest $request)
    {
        try
    	{
            $data = [
                'name' => $request->name,
                'company_id' => $request->company_id,
                'display_duration' => $request->display_duration,
                'slots_per_loop' => $request->slots_per_loop,
                'exposure_per_day' => $request->exposure_per_day,
                'is_exclusive' => ($request->is_exclusive == false) ? 0 : $request->is_exclusive,
                'is_indefinite' => ($request->is_indefinite == false) ? 0 : $request->is_indefinite,
                'active' => 1
            ];

            $contract = Contract::create($data);
            $contract->saveBrands($request->brands);
            $contract->saveScreens($request->screens);

            $contract = ContractViewModel::find($contract->id);

            return $this->response($contract, 'Successfully Created!', 200);
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

    public function updateContract(ContractRequest $request)
    {
        // try
    	// {
            $contract = Contract::find($request->id);

            $data = [
                'name' => $request->name,
                'company_id' => $request->company_id,
                'display_duration' => $request->display_duration,
                'slots_per_loop' => $request->slots_per_loop,
                'exposure_per_day' => $request->exposure_per_day,
                'is_exclusive' => ($request->is_exclusive == false) ? 0 : $request->is_exclusive,
                'is_indefinite' => ($request->is_indefinite == false) ? 0 : $request->is_indefinite,
                'active' => ($request->active == false) ? 0 : $request->active,
            ];

            $contract->update($data);
            $contract->saveBrands($request->brands);
            $contract->saveScreens($request->screens);

            $contract = ContractViewModel::find($contract->id);

            return $this->response($contract, 'Successfully Created!', 200);
        // }
        // catch (\Exception $e) 
        // {
        //     return response([
        //         'message' => $e->getMessage(),
        //         'status' => false,
        //         'status_code' => 422,
        //     ], 422);
        // }
    }

    public function deleteContract($id)
    {
        try
    	{
            $contract = Contract::find($id)->delete();
            return $this->response($contract, 'Successfully Deleted!', 200);
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
                    'name' => $company->name,
                    'parent_company' => $company->parent_company,
                    'classification_name' => $company->classification_name,
                    'email' => $company->email,
                    'contact_number' => $company->contact_number,
                    'address' => $company->address,
                    'tin_number' => $company->tin,
                    'status' => ($company->active == 1) ? 'Active' : 'Inactive',
                    'updated_at' => $company->updated_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "company.csv";
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
