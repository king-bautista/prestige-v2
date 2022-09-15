<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\BrandControllerInterface;
use Illuminate\Http\Request;

use App\Models\Brand;
use App\Models\Tag;
use App\Models\Supplemental;
use App\Models\BrandProductPromos;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\BrandViewModel;
use App\Models\ViewModels\BrandProductViewModel;

class BrandController extends AppBaseController implements BrandControllerInterface
{
    /************************************
    * 			BRANDS MANAGEMENT	 	*
    ************************************/
    public function __construct()
    {
        $this->module_id = 10; 
        $this->module_name = 'Brand Management';
    }

    public function index()
    {
        return view('admin.brands');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

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
                'status_code' => 401,
            ], 401);
        }
    }

    public function details($id)
    {
        try
        {
            $brand = BrandViewModel::find($id);
            return $this->response($brand, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function store(Request $request)
    {
        try
    	{
            $logo = $request->file('logo');
            $logo_path = '';
            if($logo) {
                $originalname = $logo->getClientOriginalName();
                $logo_path = $logo->move('uploads/media/brand/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'logo' => str_replace('\\', '/', $logo_path),
                'active' => 1
            ];

            $brand = Brand::create($data);
            $brand->saveSupplementals($request->supplementals);
            $brand->saveTags($request->tags);

            return $this->response($brand, 'Successfully Created!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function update(Request $request)
    {
        try
    	{
            $brand = Brand::find($request->id);

            $logo = $request->file('logo');
            $logo_path = '';
            if($logo) {
                $originalname = $logo->getClientOriginalName();
                $logo_path = $logo->move('uploads/media/brand/', str_replace(' ','-', $originalname)); 
            }            

            $data = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'logo' => ($logo_path) ? str_replace('\\', '/', $logo_path) : $brand->logo,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $brand->update($data);
            $brand->saveSupplementals($request->supplementals);
            $brand->saveTags($request->tags);

            return $this->response($brand, 'Successfully Modified!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function delete($id)
    {
        try
    	{
            $brand = Brand::find($id);
            $brand->delete();
            return $this->response($brand, 'Successfully Deleted!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function getSupplementals()
    {
        try
    	{
            $supplemental = Supplemental::get();
            return $this->response($supplemental, 'Successfully Deleted!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function getTags()
    {
        try
    	{
            $tags = Tag::get();
            return $this->response($tags, 'Successfully Deleted!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function brandProducts(Request $request)
    {
        try
    	{
            $brands = BrandProductViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($brands, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function allBrands()
    {
        try
    	{
            $brands = Brand::get();
            return $this->response($brands, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

}
