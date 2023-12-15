<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ProductsControllerInterface;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Thumbnails;
use Image;
use VideoThumbnail;

use App\Models\BrandProductPromos;
use App\Models\Brand;
use App\Models\AdminViewModels\BrandProductViewModel;

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
        $brand = Brand::find($id);
        return view('admin.products', compact("brand"));
    }

    public function list(Request $request)
    {
        try
        {
            $brand_id = session()->get('brand_id');
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

            $image_url = $request->file('image_url');
            $image_url_path = '';
            $thumbnails_path = '';
            if($image_url) {
                $originalname = $image_url->getClientOriginalName();
                $mime_type = explode("/", $image_url->getClientMimeType());
                $file_type = $mime_type[0];
                $image_url_path = $image_url->move('uploads/media/brand/products/', str_replace(' ','-', $originalname)); 

                $image_size = getimagesize($image_url_path);
                $required_size = 150;
                $new_width = 0;
                $new_height = 0;

                if($file_type == 'image') {
                    $width = $image_size[0];
                    $height = $image_size[1];

                    $aspect_ratio = $width/$height;
                    if ($aspect_ratio >= 1.0) {
                        $new_width = $required_size;
                        $new_height = $required_size / $aspect_ratio;
                    } else {
                        $new_width = $required_size * $aspect_ratio;
                        $new_height = $required_size;
                    }

                    $thumbnails_path = public_path('uploads/media/brand/products/thumbnails/');
                    $img = Image::make($image_url_path);
                    $img->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($thumbnails_path.str_replace(' ','-', $originalname));

                    $thumbnails_path = 'uploads/media/brand/products/thumbnails/'.str_replace(' ','-', $originalname);
                }       
            }

            $data = [
                'brand_id' => $brand_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'type' => $request->type,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'thumbnail' => str_replace('\\', '/', $thumbnails_path),
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

            $image_url = $request->file('image_url');
            $image_url_path = '';
            $thumbnails_path = '';
            if($image_url) {
                $originalname = $image_url->getClientOriginalName();
                $mime_type = explode("/", $image_url->getClientMimeType());
                $file_type = $mime_type[0];
                $image_url_path = $image_url->move('uploads/media/brand/products/', str_replace(' ','-', $originalname)); 

                $image_size = getimagesize($image_url_path);
                $required_size = 150;
                $new_width = 0;
                $new_height = 0;

                if($file_type == 'image') {
                    $width = $image_size[0];
                    $height = $image_size[1];

                    $aspect_ratio = $width/$height;
                    if ($aspect_ratio >= 1.0) {
                        $new_width = $required_size;
                        $new_height = $required_size / $aspect_ratio;
                    } else {
                        $new_width = $required_size * $aspect_ratio;
                        $new_height = $required_size;
                    }

                    $thumbnails_path = public_path('uploads/media/brand/products/thumbnails/');
                    $img = Image::make($image_url_path);
                    $img->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($thumbnails_path.str_replace(' ','-', $originalname));

                    $thumbnails_path = 'uploads/media/brand/products/thumbnails/'.str_replace(' ','-', $originalname);
                }         
            }

            $data = [
                'brand_id' => $brand_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'type' => $request->type,
                'date_from' => ($request->date_from) ? $request->date_from : null,
                'date_to' => ($request->date_to) ? $request->date_to : null,
                'thumbnail' => ($thumbnails_path) ? str_replace('\\', '/', $thumbnails_path) : $product->thumbnail,
                'image_url' => ($image_url_path) ? str_replace('\\', '/', $image_url_path) : $product->image_url,
                'active' => $this->checkBolean($request->active)
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
