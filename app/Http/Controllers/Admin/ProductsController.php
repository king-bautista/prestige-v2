<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ProductsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\ProductsRequest;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Thumbnails;
use App\Imports\BrandProductsImport;
use App\Exports\Export;
use Storage;
use Image;
use VideoThumbnail;

use App\Models\BrandProductPromos;
use App\Models\Brand;
use App\Models\CompanyBrands;
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
        try {
            $brand_id = session()->get('brand_id');
            $products = BrandProductViewModel::when(request('search'), function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'LIKE', '%' . request('search') . '%')
                        ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%')
                        ->orWhere('type', 'LIKE', '%' . request('search') . '%');
                });
            })
                ->where('brand_id', $brand_id)
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('name', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    $column = $this->checkcolumn(request('order'));
                    switch ($column) {
                        case 'thumbnail_path':
                            $field = 'thumbnail';
                            break;
                        default:
                            $field = $column;
                    }
                    return $query->orderBy($field, request('sort'));
                })
                ->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($products, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getProductsByBrand($id)
    {
        try {
            $products = BrandProductViewModel::where('brand_id', $id)->where('active', 1)->get();
            return $this->response($products, 'Successfully Retreived!', 200);
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
            $product = BrandProductViewModel::find($id);
            return $this->response($product, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(ProductsRequest $request)
    {
        try {
            $brand_id = session()->get('brand_id');

            $image_url = $request->file('image_url');
            $image_url_path = '';
            $thumbnails_path = '';
            if ($image_url) {
                $originalname = $image_url->getClientOriginalName();
                $mime_type = explode("/", $image_url->getClientMimeType());
                $file_type = $mime_type[0];
                $image_url_path = $image_url->move('uploads/media/brand/products/', str_replace(' ', '-', $originalname));

                $image_size = getimagesize($image_url_path);
                $required_size = 150;
                $new_width = 0;
                $new_height = 0;

                if ($file_type == 'image') {
                    $width = $image_size[0];
                    $height = $image_size[1];

                    $aspect_ratio = $width / $height;
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
                    })->save($thumbnails_path . str_replace(' ', '-', $originalname));

                    $thumbnails_path = 'uploads/media/brand/products/thumbnails/' . str_replace(' ', '-', $originalname);
                }
            }

            $banner_promo_image_url = $request->file('banner_promo_image_url');
            $banner_promo_image_url_path = '';
            $banner_promo_thumbnails_path = '';
            if ($banner_promo_image_url) {
                $banner_promo_originalname = $banner_promo_image_url->getClientOriginalName();
                $banner_promo_mime_type = explode("/", $banner_promo_image_url->getClientMimeType());
                $banner_promo_file_type = $banner_promo_mime_type[0];
                $banner_promo_image_url_path = $banner_promo_image_url->move('uploads/media/brand/products/', str_replace(' ', '-', $banner_promo_originalname));

                $banner_promo_image_size = getimagesize($banner_promo_image_url_path);
                $banner_promo_required_size = 150;
                $banner_promo_new_width = 0;
                $banner_promo_new_height = 0;

                if ($banner_promo_file_type == 'image') {
                    $banner_promo_width = $banner_promo_image_size[0];
                    $banner_promo_height = $banner_promo_image_size[1];

                    $banner_promo_aspect_ratio = $banner_promo_width / $banner_promo_height;
                    if ($banner_promo_aspect_ratio >= 1.0) {
                        $banner_promo_new_width = $banner_promo_required_size;
                        $banner_promo_new_height = $banner_promo_required_size / $banner_promo_aspect_ratio;
                    } else {
                        $banner_promo_new_width = $banner_promo_required_size * $banner_promo_aspect_ratio;
                        $banner_promo_new_height = $banner_promo_required_size;
                    }

                    $banner_promo_thumbnails_path = public_path('uploads/media/brand/products/thumbnails/');
                    $banner_promo_img = Image::make($banner_promo_image_url_path);
                    $banner_promo_img->resize($banner_promo_new_width, $banner_promo_new_height, function ($banner_promo_constraint) {
                        $banner_promo_constraint->aspectRatio();
                    })->save($banner_promo_thumbnails_path . str_replace(' ', '-', $banner_promo_originalname));

                    $banner_promo_thumbnails_path = 'uploads/media/brand/products/thumbnails/' . str_replace(' ', '-', $banner_promo_originalname);
                }
            }

            $data = [
                'brand_id' => $brand_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'type' => $request->type,
                'date_from' => ($request->date_from == '0000-00-00') ? NULL : $request->date_from,
                'date_to' => ($request->date_to == '0000-00-00') ? NULL : $request->date_to,
                'thumbnail' => str_replace('\\', '/', $thumbnails_path),
                'image_url' => str_replace('\\', '/', $image_url_path),
                'banner_promo_thumbnail' => str_replace('\\', '/', $banner_promo_thumbnails_path),
                'banner_promo_image_url' => str_replace('\\', '/', $banner_promo_image_url_path),
                'active' => 1
            ];

            $product = BrandProductPromos::create($data);

            return $this->response($product, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(ProductsRequest $request)
    {
        try {
            $brand_id = session()->get('brand_id');
            $product = BrandProductPromos::find($request->id);

            $image_url = $request->file('image_url');
            $image_url_path = '';
            $thumbnails_path = '';
            if ($image_url) {
                $originalname = $image_url->getClientOriginalName();
                $mime_type = explode("/", $image_url->getClientMimeType());
                $file_type = $mime_type[0];
                $image_url_path = $image_url->move('uploads/media/brand/products/', str_replace(' ', '-', $originalname));

                $image_size = getimagesize($image_url_path);
                $required_size = 150;
                $new_width = 0;
                $new_height = 0;

                if ($file_type == 'image') {
                    $width = $image_size[0];
                    $height = $image_size[1];

                    $aspect_ratio = $width / $height;
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
                    })->save($thumbnails_path . str_replace(' ', '-', $originalname));

                    $thumbnails_path = 'uploads/media/brand/products/thumbnails/' . str_replace(' ', '-', $originalname);
                }
            }

            $data = [
                'brand_id' => $brand_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'type' => $request->type,
                'date_from' => ($request->date_from == '0000-00-00') ? NULL : $request->date_from,
                'date_to' => ($request->date_to == '0000-00-00') ? NULL : $request->date_to,
                'thumbnail' => ($thumbnails_path) ? str_replace('\\', '/', $thumbnails_path) : $product->thumbnail,
                'image_url' => ($image_url_path) ? str_replace('\\', '/', $image_url_path) : $product->image_url,
                'active' => $this->checkBolean($request->active)
            ];

            $product->update($data);

            return $this->response($product, 'Successfully Modified!', 200);
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
            $product = BrandProductPromos::find($id);
            $product->delete();
            return $this->response($product, 'Successfully Deleted!', 200);
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
            Excel::import(new BrandProductsImport, $request->file('file'));
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
            $brand_id = session()->get('brand_id');
            $brand_management =  BrandProductViewModel::where('brand_id', $brand_id)->get();
            $reports = [];
            foreach ($brand_management as $brand) {
                $reports[] = [
                    'id' => $brand->id,
                    'brand_id' => $brand->brand_id,
                    'brand_name' => $brand->brand_name,
                    'name' => $brand->name,
                    'descriptions' => $brand->descriptions,
                    'type' => $brand->type,
                    'thumbnail' => $brand->thumbnail,
                    'image_url' => $brand->image_url,
                    'date_from' => $brand->date_from,
                    'date_to' => $brand->date_to,
                    'sequence' => $brand->sequence,
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

            $filename = $brand_id."_brand-products-management.csv";
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

    public function downloadCsvtemplate()
    {
        try {
            $brand_id = session()->get('brand_id');
            $reports[] = [
                'id' => '',
                    'brand_id' => '',
                    'brand_name' => '',
                    'name' => '',
                    'descriptions' => '',
                    'type' => '',
                    'thumbnail' => '',
                    'image_url' => '',
                    'date_from' => '',
                    'date_to' => '',
                    'sequence' => '',
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

            $filename = $brand_id."_brand-products-management-template.csv";
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
