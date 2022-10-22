<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CompaniesControllerInterface;
use Illuminate\Http\Request;

use App\Models\Company;
use App\Models\ViewModels\CompanyViewModel;
use App\Models\ViewModels\AdminViewModel;

class CompaniesController extends AppBaseController implements CompaniesControllerInterface
{
    /************************************
    * 			BRANDS MANAGEMENT	 	*
    ************************************/
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

    public function store(Request $request)
    {
        try
    	{
            $data = [
                'classification_id' => $request->classification_id,
                'name' => $request->name,
                'address' => $request->address,
                'tin' => $request->tin,
                'active' => 1
            ];

            $company = Company::create($data);

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

    public function update(Request $request)
    {
        try
    	{
            $company = Company::find($request->id);

            $data = [
                'classification_id' => $request->classification_id,
                'name' => $request->name,
                'address' => $request->address,
                'tin' => $request->tin,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $company->update($data);

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
            $companies = Company::get();
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
}
