<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\BrandControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Models\Brand;
use App\Models\Tag;
use App\Models\Supplemental;
use App\Models\BrandProductPromos;
use App\Models\CompanyBrands;
use App\Models\AdminViewModels\UserViewModel;
use App\Models\AdminViewModels\BrandViewModel;
use App\Models\AdminViewModels\BrandProductViewModel;

use App\Imports\BrandsImport;

class BrandController extends AppBaseController implements BrandControllerInterface
{
    /************************************
    * 			BRANDS MANAGEMENT	 	*
    ************************************/
    public function __construct()
    {
        $this->module_id = 48; 
        $this->module_name = 'User Brands';
    }

    public function index()
    {
        return view('portal.brands');
    }

    public function list(Request $request)
    {
        // try
        // {
            $id = Auth::guard('portal')->user()->id;
            $user = UserViewModel::find($id);
            $brand_ids = $user->getBrandIds();

            $brands = BrandViewModel::when(request('search'), function($query){
                return $query->where('brands.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('brands.descriptions', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('categories.name', 'LIKE', '%' . request('search') . '%');
            })
            ->whereIn('brands.id', $brand_ids)
            ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
            ->select('brands.*')
            ->latest()
            ->paginate(request('perPage'));

            return $this->responsePaginate($brands, 'Successfully Retreived!', 200);
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

    public function details($id)
    {
        try
        {
            $amenities = Amenity::find($id);
            return $this->response($amenities, 'Successfully Retreived!', 200);
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
                'name' => $request->name,
                'active' => 1
            ];

            $amenities = Amenity::create($data);

            return $this->response($amenities, 'Successfully Created!', 200);
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
            $amenities = Amenity::find($request->id);

            $data = [
                'name' => $request->name,
                'active' => $request->active
            ];

            $amenities->update($data);

            return $this->response($amenities, 'Successfully Modified!', 200);
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
            $user_id = Auth::guard('portal')->user()->id;
            $user = UserViewModel::find($user_id);

            $company_brand = CompanyBrands::where('brand_id', $id)->where('company_id', $user->company_id)->delete();

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

    public function allBrands(Request $request)
    {
        try
    	{
            $brands = BrandViewModel::when(request('search'), function($query){
                return $query->where('brands.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('brands.descriptions', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('categories.name', 'LIKE', '%' . request('search') . '%');
            })
            ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
            ->select('brands.*')
            ->latest()
            ->paginate(request('perPage'));

            return $this->responsePaginate($brands, 'Successfully Retreived!', 200);
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
            $id = Auth::guard('portal')->user()->id;
            $user = UserViewModel::find($id);

            $company_brand = CompanyBrands::updateOrCreate(
                [
                    'company_id' => $user->company_id,
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

}
