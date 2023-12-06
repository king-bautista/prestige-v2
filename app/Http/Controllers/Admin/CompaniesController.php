<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CompaniesControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\Export;
use Storage;

use App\Http\Requests\CompanyRequest;
use App\Http\Requests\ContractRequest;

use App\Models\Company;
use App\Models\CompanyBrands;
use App\Models\Contract;
use App\Models\AdminViewModels\CompanyViewModel;
use App\Models\AdminViewModels\ContractViewModel;

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
                'parent_id' => ($request->parent_id) ? $request->parent_id : null,
                'classification_id' => $request->classification_id,
                'name' => $request->name,
                'email' => ($request->email) ? $request->email : null,
                'contact_number' => ($request->contact_number) ? $request->contact_number : null,
                'address' => ($request->address) ? $request->address : null,
                'tin' => ($request->tin) ? $request->tin : null,
                'active' => 1,
            ];

            $company = Company::create($data);
            $company->saveBrands($request->brands);
            $company = CompanyViewModel::find($company->id);

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
                'parent_id' => ($request->parent_id) ? $request->parent_id : null,
                'classification_id' => $request->classification_id,
                'name' => $request->name,
                'email' => ($request->email) ? $request->email : null,
                'contact_number' => ($request->contact_number) ? $request->contact_number : null,
                'address' => ($request->address) ? $request->address : null,
                'tin' => ($request->tin) ? $request->tin : null,
                'active' => $this->checkBolean($request->active),
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
                'reference_code' => ($request->reference_code) ? $request->reference_code : null,
                'business_id' => ($request->business_id) ? $request->business_id : null,
                'remarks' => ($request->remarks) ? $request->remarks : null,
                'company_id' => ($request->company_id) ? $request->company_id : null,
                'display_duration' => ($request->display_duration) ? $request->display_duration : 0,
                'slots_per_loop' => ($request->slots_per_loop) ? $request->slots_per_loop : 0,
                'exposure_per_day' => ($request->exposure_per_day) ? $request->exposure_per_day : 0,
                'start_date' => ($request->start_date) ? $request->start_date: null,
                'end_date' => ($request->end_date) ? $request->end_date : null,
                'is_exclusive' => $this->checkBolean($request->is_exclusive),
                'is_indefinite' => $this->checkBolean($request->is_indefinite),
                'active' => 1
            ];

            $contract = Contract::create($data);
            $contract->serial_number = 'CTR-'.Str::padLeft($contract->id, 5, '0');
            $contract->save();

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
        try
    	{
            $contract = Contract::find($request->id);

            $data = [
                'serial_number' => ($contract->serial_number) ? $contract->serial_number : 'CTR-'.Str::padLeft($contract->id, 5, '0'),
                'name' => $request->name,
                'reference_code' => ($request->reference_code) ? $request->reference_code : null,
                'business_id' => ($request->business_id) ? $request->business_id : null,
                'remarks' => ($request->remarks) ? $request->remarks : null,
                'company_id' => ($request->company_id) ? $request->company_id : null,
                'display_duration' => ($request->display_duration) ? $request->display_duration : 0,
                'slots_per_loop' => ($request->slots_per_loop) ? $request->slots_per_loop : 0,
                'exposure_per_day' => ($request->exposure_per_day) ? $request->exposure_per_day : 0,
                'start_date' => ($request->start_date) ? $request->start_date: null,
                'end_date' => ($request->end_date) ? $request->end_date : null,
                'is_exclusive' => $this->checkBolean($request->is_exclusive),
                'is_indefinite' => $this->checkBolean($request->is_indefinite),
                'active' => $this->checkBolean($request->active),
            ];

            $contract->update($data);
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

    function duplicateContract($id) 
    {
        try
    	{
            $contract = Contract::find($id)->toArray();
            $contract_details = ContractViewModel::find($id);

            $new_contract = Contract::create($contract);
            $new_contract->serial_number = 'CTR-'.Str::padLeft($new_contract->id, 5, '0');
            $new_contract->save();

            $new_contract->saveBrands($contract_details->brands->toArray());
            $new_contract->saveScreens($contract_details->screens->toArray());

            $new_contract = ContractViewModel::find($new_contract->id);

            return $this->response($new_contract, 'Successfully Deleted!', 200);
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