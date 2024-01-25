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
        try
        {
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
            $brand = BrandViewModel::find($id);
            return $this->response($brand, 'Successfully Retreived!', 200);
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
            $id = Auth::guard('portal')->user()->id;
            $user = UserViewModel::find($id);

            $logo = $request->file('logo');
            $logo_path = '';
            $thumbnails_path = '';
            if($logo) {
                $originalname = $logo->getClientOriginalName();
                $mime_type = explode("/", $logo->getClientMimeType());
                $file_type = $mime_type[0];
                $logo_path = $logo->move('uploads/media/brand/', str_replace(' ','-', $originalname)); 

                $image_size = getimagesize($logo_path);
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

                    $thumbnails_path = public_path('uploads/media/brand/thumbnails/');
                    $img = Image::make($logo_path);
                    $img->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($thumbnails_path.str_replace(' ','-', $originalname));

                    $thumbnails_path = 'uploads/media/brand/thumbnails/'.str_replace(' ','-', $originalname);
                }
            } 

            $data = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'descriptions' => ($request->descriptions) ? $request->descriptions : null,
                'logo' => str_replace('\\', '/', $logo_path),
                'thumbnail' => str_replace('\\', '/', $thumbnails_path),
                'active' => 1
            ];

            $brand = Brand::create($data);
            $brand->saveSupplementals($request->supplementals);
            $brand->saveTags($request->tags);
            $company_brand = CompanyBrands::updateOrCreate(
                [
                    'company_id' => $user->company_id,
                    'brand_id' => $brand->id
                ],
            );

            return $this->response($brand, 'Successfully Created!', 200);
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
            $brand = Brand::find($request->id);
            $brand->touch();

            $logo = $request->file('logo');
            $logo_path = '';
            $thumbnails_path = '';
            if($logo) {
                $originalname = $logo->getClientOriginalName();
                $mime_type = explode("/", $logo->getClientMimeType());
                $file_type = $mime_type[0];
                $logo_path = $logo->move('uploads/media/brand/', str_replace(' ','-', $originalname)); 

                $image_size = getimagesize($logo_path);
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

                    $thumbnails_path = public_path('uploads/media/brand/thumbnails/');
                    $img = Image::make($logo_path);
                    $img->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($thumbnails_path.str_replace(' ','-', $originalname));

                    $thumbnails_path = 'uploads/media/brand/thumbnails/'.str_replace(' ','-', $originalname);
                }
            }            

            $data = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'descriptions' => ($request->descriptions) ? $request->descriptions : null,
                'logo' => ($logo_path) ? str_replace('\\', '/', $logo_path) : $brand->logo,
                'thumbnail' => ($thumbnails_path) ? str_replace('\\', '/', $thumbnails_path) : $brand->thumbnail,
                'active' => $this->checkBolean($request->active),
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
