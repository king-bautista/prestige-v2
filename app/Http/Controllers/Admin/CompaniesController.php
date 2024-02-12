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
use Session;

use App\Http\Requests\CompanyRequest;
use App\Http\Requests\ContractRequest;
use App\Imports\CompaniesImport;


use App\Models\Company;
use App\Models\CompanyBrands;
use App\Models\Contract;
use App\Models\AdminViewModels\CompanyViewModel;
use App\Models\AdminViewModels\BrandViewModel;
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
        $this->deleteCompanySession();
        return view('admin.companies');
    }

    public function list(Request $request)
    {
        try {
            $companies = CompanyViewModel::when(request('search'), function ($query) {
                return $query->where('companies.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('companies.email', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('companies.contact_number', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('classifications.name', 'LIKE', '%' . request('search') . '%');
            })
                ->leftJoin('classifications', 'companies.classification_id', '=', 'classifications.id')
                ->select('companies.*', 'classifications.name', 'companies.name')
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('companies.name', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    $column = $this->checkcolumn(request('order'));
                    if ($column == 'classification_name') {
                        $field = 'classifications.name';
                    } else if ($column == 'name') {
                        $field = 'companies.name';
                    } else {
                        $field = $column;
                    }
                    return $query->orderBy($field, request('sort'));
                })
                ->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($companies, 'Successfully Retreived!', 200);
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
            $this->setCompanySession($id);

            $company = CompanyViewModel::find($id);
            return $this->response($company, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(CompanyRequest $request)
    {
        try {
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
            $this->setCompanySession($company->id);
            $company = CompanyViewModel::find($company->id);

            return $this->response($company, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(CompanyRequest $request)
    {
        try {
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
            $company = Company::find($id);
            $company->delete();
            return $this->response($company, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getAll()
    {
        try {
            $companies = CompanyViewModel::get();
            return $this->response($companies, 'Successfully Retreived!', 200);
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
            $companies = CompanyViewModel::whereNull('parent_id')->where('active', 1)->get();
            return $this->response($companies, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getBrands($company_id)
    {
        try {
            $ids = CompanyBrands::where('company_id', $company_id)->get()->pluck('brand_id');
            $brands = BrandViewModel::whereIn('id', $ids)->get();
            return $this->response($brands, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function storeBrand(Request $request)
    {
        try {
            $company_brand = CompanyBrands::updateOrCreate(
                [
                    'company_id' => $request->company_id,
                    'brand_id' => $request->id
                ],
            );
            return $this->response($company_brand, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function deleteBrand($id)
    {
        try {
            $company_id = session()->get('company_id');
            $company_brand = CompanyBrands::where('brand_id', $id)->where('company_id', $company_id)->delete();
            return $this->response($company_brand, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function contractDetails($id)
    {
        try {
            $contract = ContractViewModel::find($id);
            return $this->response($contract, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function storeContract(ContractRequest $request)
    {
        try {
            $data = [
                'name' => $request->name,
                'reference_code' => ($request->reference_code) ? $request->reference_code : null,
                'business_id' => ($request->business_id) ? $request->business_id : null,
                'remarks' => ($request->remarks) ? $request->remarks : null,
                'company_id' => ($request->company_id) ? $request->company_id : null,
                'display_duration' => ($request->display_duration) ? $request->display_duration : 0,
                'slots_per_loop' => ($request->slots_per_loop) ? $request->slots_per_loop : 0,
                'exposure_per_day' => ($request->exposure_per_day) ? $request->exposure_per_day : 0,
                'start_date' => ($request->start_date) ? $request->start_date : null,
                'end_date' => ($request->end_date) ? $request->end_date : null,
                'is_exclusive' => $this->checkBolean($request->is_exclusive),
                'is_indefinite' => $this->checkBolean($request->is_indefinite),
                'active' => 1
            ];

            $contract = Contract::create($data);
            $contract->serial_number = 'CTR-' . Str::padLeft($contract->id, 5, '0');
            $contract->save();

            $contract->saveBrands($request->brands);
            $contract->saveScreens($request->screens);

            $contract = ContractViewModel::find($contract->id);

            return $this->response($contract, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function updateContract(ContractRequest $request)
    {
        try {
            $contract = Contract::find($request->id);

            $data = [
                'serial_number' => ($contract->serial_number) ? $contract->serial_number : 'CTR-' . Str::padLeft($contract->id, 5, '0'),
                'name' => $request->name,
                'reference_code' => ($request->reference_code) ? $request->reference_code : null,
                'business_id' => ($request->business_id) ? $request->business_id : null,
                'remarks' => ($request->remarks) ? $request->remarks : null,
                'company_id' => ($request->company_id) ? $request->company_id : null,
                'display_duration' => ($request->display_duration) ? $request->display_duration : 0,
                'slots_per_loop' => ($request->slots_per_loop) ? $request->slots_per_loop : 0,
                'exposure_per_day' => ($request->exposure_per_day) ? $request->exposure_per_day : 0,
                'start_date' => ($request->start_date) ? $request->start_date : null,
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
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function deleteContract($id)
    {
        try {
            $contract = Contract::find($id)->delete();
            return $this->response($contract, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    function duplicateContract($id)
    {
        try {
            $contract = Contract::find($id)->toArray();
            $contract_details = ContractViewModel::find($id);

            $new_contract = Contract::create($contract);
            $new_contract->serial_number = 'CTR-' . Str::padLeft($new_contract->id, 5, '0');
            $new_contract->save();

            $new_contract->saveBrands($contract_details->brands->toArray());
            $new_contract->saveScreens($contract_details->screens->toArray());

            $new_contract = ContractViewModel::find($new_contract->id);

            return $this->response($new_contract, 'Successfully Deleted!', 200);
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
            Excel::import(new CompaniesImport, $request->file('file'));
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

            $company_management = CompanyViewModel::get();
            $reports = [];
            foreach ($company_management as $company) {
                $reports[] = [
                    'id' => $company->id,
                    'parent_id' => $company->parent_id,
                    'name' => $company->name,
                    'classification_id' => $company->classification_details['id'],
                    'classification_name' => $company->classification_details['name'],
                    'email' => $company->email,
                    'contact_number' => $company->contact_number,
                    'address' => $company->address,
                    'tin' => $company->tin,
                    'active' => $company->active,
                    'created_at' => $company->created_at,
                    'updated_at' => $company->updated_at,
                    'deleted_at' => $company->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }
            $company_name = session()->get('company_name');
            $filename = "company(" . $company_name . ").csv";
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
                'parent_id' => '',
                'name' => '',
                'classification_id' => '',
                'classification_name' => '',
                'email' => '',
                'contact_number' => '',
                'address' => '',
                'tin' => '',
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
            $company_name = session()->get('company_name');
            $filename = "company(" . $company_name . ")-template.csv";
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

    public function listBrands(Request $request)
    {
        try {
            if (Session::has('company_id')) {
                $company_id = session()->get('company_id');
                $brand_ids = CompanyBrands::where('company_id', $company_id)->get()->pluck('brand_id');
            } else {

                $company_id = 0;
                $brand_ids = CompanyBrands::where('company_id', $company_id)->get()->pluck('brand_id');
            }

            $brands = BrandViewModel::when(request('search'), function ($query) {
                return $query->where('brands.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('brands.descriptions', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('supplementals.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('categories.name', 'LIKE', '%' . request('search') . '%');
            })

                ->whereIn('brands.id', $brand_ids)
                ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
                ->leftJoin('supplementals', 'brands.category_id', '=', 'supplementals.id')
                ->select('brands.*', 'categories.name', 'supplementals.name', 'brands.name')
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('brands.name', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    $column = $this->checkcolumn(request('order'));
                    if ($column == 'category_name') {
                        $field = 'categories.name';
                    } else if ($column == 'supplemental_names') {
                        $field = 'supplementals.name';
                    } else if ($column == 'name') {
                        $field = 'brands.name';
                    } else {
                        $field = $column;
                    }
                    return $query->orderBy($field, request('sort'));
                })
                ->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($brands, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvBrand()
    {
        try {
            if (Session::has('company_id')) {
                $company_id = session()->get('company_id');
                $company_name = session()->get('company_name');
                $brand_ids = CompanyBrands::where('company_id', $company_id)->get()->pluck('brand_id');
                $brands = BrandViewModel::whereIn('brands.id', $brand_ids)->get();

                $reports = [];
                foreach ($brands as $brand) {
                    $reports[] = [
                        'id' => $brand->id,
                        'category_id' => $brand->category_id,
                        'category_name' => $brand->category_name,
                        'name' => $brand->name,
                        'descriptions' => $brand->descriptions,
                        'logo' => $brand->logo,
                        'thumbnail' => $brand->thumbnail,
                        'active' => $brand->active,
                        'created_at' => $brand->created_at,
                        'updated_at' => $brand->updated_at,
                        'deleted_at' => $brand->deleted_at,
                    ];
                }

                $directory = 'public/export/reports/';
                $files = Storage::files($directory);
                foreach ($files as $file) {
                    Storage::delete($file);
                }

                $filename = "company(" . $company_name . ")-brand.csv";
                // Store on default disk
                Excel::store(new Export($reports), $directory . $filename);

                $data = [
                    'filepath' => '/storage/export/reports/' . $filename,
                    'filename' => $filename
                ];

                if (Storage::exists($directory . $filename))
                    return $this->response($data, 'Successfully Retreived!', 200);

                return $this->response(false, 'Successfully Retreived!', 200);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvTemplateBrand()
    {
        $company_name = session()->get('company_name');
        try {
            $reports[] = [
                'id' => '',
                'category_id' => '',
                'category_name' => '',
                'name' => '',
                'descriptions' => '',
                'logo' => '',
                'thumbnail' => '',
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

            $filename = "company(" . $company_name . ")-brand-template.csv";
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

    public function listContracts(Request $request)
    {
        try {
            if (Session::has('company_id')) {
                $company_id = session()->get('company_id');
            } else {
                $company_id = 0;
            }

            $contracts = ContractViewModel::when(request('search'), function ($query) {
                return $query->where('contracts.name', 'LIKE', '%' . request('search') . '%');
            })
                ->leftJoin('contract_screens', 'contracts.id', '=', 'contract_screens.contract_id')
                // ->leftJoin('site_screens', 'contract_screens.site_screen_id', '=', 'site_screens.id')

                // ->leftJoin('site_buildings', 'site_screens.site_building_id', '=', 'site_buildings.id')
                // ->leftJoin('site_building_levels', 'site_screens.site_building_level_id', '=', 'site_building_levels.id')
                // ->select('companies.*', 'classifications.name', 'companies.name')
                ->where('contracts.company_id', $company_id)
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('contracts.name', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    $column = $this->checkcolumn(request('order'));
                    // if ($column == 'classification_name') {
                    //     $field = 'classifications.name';
                    // }else if ($column == 'name') {
                    //     $field = 'companies.name';
                    // } else {
                    $field = $column;
                    //}
                    return $query->orderBy($field, request('sort'));
                })
                //->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($contracts, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvContract()
    {
        try {
            if (Session::has('company_id')) {
                $company_id = session()->get('company_id');
                $company_name = session()->get('company_name');
                $contracts = ContractViewModel::where('contracts.company_id', $company_id)->get();
                $reports = [];
                foreach ($contracts as $contract) {
                    $reports[] = [
                        'id' => $contract->serial_number,
                        'company_id' => $company_id,
                        'company_name' => $company_name,
                        'brand_names' => $contract->brand_names,
                        'name' => $contract->name,
                        'screen_locations' => $contract->screen_locations,
                        'reference_code' => $contract->reference_code,
                        'business_id' => $contract->business_id,
                        'remarks' => $contract->remarks,
                        'is_indefinite' => $contract->is_indefinite,
                        'is_exclusive' => $contract->is_exclusive,
                        'display_duration' => $contract->display_duration,
                        'slots_per_loop' => $contract->slots_per_loop,
                        'exposure_per_day' => $contract->exposure_per_day,
                        'start_date' => $contract->start_date,
                        'end_date' => $contract->end_date,
                        'active' => $contract->active,
                        'created_at' => $contract->created_at,
                        'updated_at' => $contract->updated_at,
                        'deleted_at' => $contract->deleted_at,
                    ];
                }

                $directory = 'public/export/reports/';
                $files = Storage::files($directory);
                foreach ($files as $file) {
                    Storage::delete($file);
                }

                $filename = "company(" . $company_name . ")-contract.csv";
                // Store on default disk
                Excel::store(new Export($reports), $directory . $filename);

                $data = [
                    'filepath' => '/storage/export/reports/' . $filename,
                    'filename' => $filename
                ];

                if (Storage::exists($directory . $filename))
                    return $this->response($data, 'Successfully Retreived!', 200);

                return $this->response(false, 'Successfully Retreived!', 200);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvTemplateContract()
    {
        try {
            $reports[] = [
                'id' => '',
                'company_id' => '',
                'company_name' => '',
                'brand_names' => '',
                'name' => '',
                'screen_locations' => '',
                'reference_code' => '',
                'business_id' => '',
                'remarks' => '',
                'is_indefinite' => '',
                'is_exclusive' => '',
                'display_duration' => '',
                'slots_per_loop' => '',
                'exposure_per_day' => '',
                'start_date' => '',
                'end_date' => '',
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
            $company_name = session()->get('company_name');
            $filename = "company(" . $company_name . ")-brand-template.csv";
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
    public function setCompanySession($id)
    {
        session()->forget('company_id');
        session()->forget('company_name');
        session()->put('company_id', $id);
        $company_name = Company::find($id)['name'];
        session()->put('company_name', $company_name);
    }
    public function deleteCompanySession()
    {
        session()->forget('company_id');
        session()->forget('company_name');
    }
}
