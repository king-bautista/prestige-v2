<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ProductsControllerInterface;
use Illuminate\Http\Request;

use App\Models\BrandProductPromos;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\BrandViewModel;
use App\Models\ViewModels\BrandProductViewModel;

class ProductsController extends AppBaseController implements ProductsControllerInterface
{
    protected $brand_id;
    /********************************************
    * 			BRANDS PRODUCTS MANAGEMENT	 	*
    ********************************************/
    public function __construct()
    {
        $this->module_id = 10; 
        $this->module_name = 'Brand Product Management';
    }

    public function index($id)
    {
        session()->forget('brand_id');
        session()->put('brand_id', $id);
        $brand = BrandViewModel::find($id);
        return view('admin.products', compact("brand"));
    }

    public function list(Request $request)
    {
        try
        {
            $brand_id = session()->get('brand_id');
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $products = BrandProductViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%');
            })
            ->where('brand_id', $brand_id)
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($products, 'Successfully Retreived!', 200);
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

    public function getProductsByBrand($id)
    {
        try
        {
            $products = BrandProductViewModel::where('brand_id', $id)->where('active', 1)->get();
            return $this->response($products, 'Successfully Retreived!', 200);
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
            $product = BrandProductViewModel::find($id);
            return $this->response($product, 'Successfully Retreived!', 200);
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
            $brand_id = session()->get('brand_id');

            $thumbnail = $request->file('thumbnail');
            $thumbnail_path = '';
            if($thumbnail) {
                $originalname = $thumbnail->getClientOriginalName();
                $thumbnail_path = $thumbnail->move('uploads/media/brand/products/', str_replace(' ','-', $originalname)); 
            }

            $image_url = $request->file('image_url');
            $image_url_path = '';
            if($image_url) {
                $originalname = $image_url->getClientOriginalName();
                $image_url_path = $image_url->move('uploads/media/brand/products/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'brand_id' => $brand_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'type' => $request->type,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'thumbnail' => str_replace('\\', '/', $thumbnail_path),
                'image_url' => str_replace('\\', '/', $image_url_path),
                'active' => 1
            ];

            $product = BrandProductPromos::create($data);

            return $this->response($product, 'Successfully Created!', 200);
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
            $brand_id = session()->get('brand_id');
            $product = BrandProductPromos::find($request->id);

            $thumbnail = $request->file('thumbnail');
            $thumbnail_path = '';
            if($thumbnail) {
                $originalname = $thumbnail->getClientOriginalName();
                $thumbnail_path = $thumbnail->move('uploads/media/brand/products/', str_replace(' ','-', $originalname)); 
            }

            $image_url = $request->file('image_url');
            $image_url_path = '';
            if($image_url) {
                $originalname = $image_url->getClientOriginalName();
                $image_url_path = $image_url->move('uploads/media/brand/products/', str_replace(' ','-', $originalname)); 
            }         

            $data = [
                'brand_id' => $brand_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'type' => $request->type,
                'date_from' => ($request->date_from) ? $request->date_from : null,
                'date_to' => ($request->date_to) ? $request->date_to : null,
                'thumbnail' => ($thumbnail_path) ? str_replace('\\', '/', $thumbnail_path) : $product->thumbnail,
                'image_url' => ($image_url_path) ? str_replace('\\', '/', $image_url_path) : $product->image_url,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $product->update($data);

            return $this->response($product, 'Successfully Modified!', 200);
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
            $product = BrandProductPromos::find($id);
            $product->delete();
            return $this->response($product, 'Successfully Deleted!', 200);
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
