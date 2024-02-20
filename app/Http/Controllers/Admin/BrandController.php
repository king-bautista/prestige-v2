<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\BrandControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Thumbnails;

use App\Http\Requests\BrandRequest;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Supplemental;
use App\Models\BrandProductPromos;
use App\Models\CompanyBrands;
use App\Models\AdminViewModels\BrandViewModel;
use App\Models\AdminViewModels\BrandProductViewModel;

use App\Imports\BrandsImport;
use App\Exports\Export;
use VideoThumbnail;
use Storage;
use Image;
use URL;
use Session;

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
        try {
            if (Session::has('company_id')) {
                $company_id = session()->get('company_id');
                $brand_ids = CompanyBrands::where('company_id', $company_id)->get()->pluck('brand_id');
            } else {
                $filters = json_decode($request->filters);
                $company_id = null;
                $brand_ids = [];
                if ($filters) {
                    $company_id = $filters->company_id;
                    $brand_ids = CompanyBrands::where('company_id', $company_id)->get()->pluck('brand_id');
                }
            }

            $brands = BrandViewModel::when(request('search'), function ($query) {
                return $query->where('brands.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('brands.descriptions', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('supplementals', 'LIKE', '%' . request('search') . '%');
                    
            })
                ->when(count($brand_ids) > 0, function ($query) use ($brand_ids) {
                    return $query->whereIn('brands.id', $brand_ids);
                })
                ->leftJoin('brand_supplementals as bs', 'brands.id', '=', 'bs.brand_id')
                ->leftJoin('categories as c', 'bs.supplemental_id','=', 'c.id')
                ->select('brands.*', 'c.name as c_name', 'brands.name as b_name', DB::raw('group_concat(c.name) as supplementals'))
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('brands.name', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    $column = $this->checkcolumn(request('order'));
                    if ($column == 'category_name') {
                        $field = 'c.name';
                    } else if ($column == 'supplemental_names') {
                        $field = 'supplementals';
                    } else if ($column == 'name') {
                        $field = 'brands.name';
                    } else {
                        $field = $column;
                    }
                    return $query->orderBy($field, request('sort'));
                })
                ->groupBy('brands.id')
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

    public function listModal(Request $request)
    {
        try {
            $filters = json_decode($request->filters);
            $company_id = null;
            $brand_ids = [];
            if ($filters) {
                $company_id = $filters->company_id;
                $brand_ids = CompanyBrands::where('company_id', $company_id)->get()->pluck('brand_id');
            }

            $brands = BrandViewModel::when(request('search'), function ($query) {
                return $query->where('brands.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('brands.descriptions', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('supplementals.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('categories.name', 'LIKE', '%' . request('search') . '%');
            })
                ->when(count($brand_ids) > 0, function ($query) use ($brand_ids) {
                    return $query->whereIn('brands.id', $brand_ids);
                })
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

    public function details($id)
    {
        try {
            $brand = BrandViewModel::find($id);
            return $this->response($brand, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(BrandRequest $request)
    {
        try {
            $logo = $request->file('logo');
            $logo_path = '';
            $thumbnails_path = '';
            if ($logo) {
                $originalname = $logo->getClientOriginalName();
                $mime_type = explode("/", $logo->getClientMimeType());
                $file_type = $mime_type[0];
                $logo_path = $logo->move('uploads/media/brand/', str_replace(' ', '-', $originalname));

                $image_size = getimagesize($logo_path);
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

                    $thumbnails_path = public_path('uploads/media/brand/thumbnails/');
                    $img = Image::make($logo_path);
                    $img->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($thumbnails_path . str_replace(' ', '-', $originalname));

                    $thumbnails_path = 'uploads/media/brand/thumbnails/' . str_replace(' ', '-', $originalname);
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

            return $this->response($brand, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(BrandRequest $request)
    {
        try {
            $brand = Brand::find($request->id);
            $brand->touch();

            $logo = $request->file('logo');
            $logo_path = '';
            $thumbnails_path = '';
            if ($logo) {
                $originalname = $logo->getClientOriginalName();
                $mime_type = explode("/", $logo->getClientMimeType());
                $file_type = $mime_type[0];
                $logo_path = $logo->move('uploads/media/brand/', str_replace(' ', '-', $originalname));

                $image_size = getimagesize($logo_path);
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

                    $thumbnails_path = public_path('uploads/media/brand/thumbnails/');
                    $img = Image::make($logo_path);
                    $img->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($thumbnails_path . str_replace(' ', '-', $originalname));

                    $thumbnails_path = 'uploads/media/brand/thumbnails/' . str_replace(' ', '-', $originalname);
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
            $brand = Brand::find($id);
            $brand->delete();
            return $this->response($brand, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getSupplementals()
    {
        try {
            $supplemental = Supplemental::get();
            return $this->response($supplemental, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTags()
    {
        try {
            $tags = Tag::get();
            return $this->response($tags, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function brandProducts(Request $request)
    {
        try {
            $brands = BrandProductViewModel::when(request('search'), function ($query) {
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%');
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

    public function allBrands()
    {
        try {
            $brands = BrandViewModel::get();
            return $this->response($brands, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function searchBrands(Request $request)
    {
        try {
            $brands = BrandViewModel::where('name', 'LIKE', request('search') . '%')->get();
            return $this->response($brands, 'Successfully Retreived!', 200);
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
            Excel::import(new BrandsImport, $request->file('file'));
            return $this->response(true, 'Successfully Uploaded!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsv($ids)
    {
        try {
            $between = explode("_", $ids);
            $brand_management =  BrandViewModel::skip($between[0])->take(1000)->get();


            $reports = [];
            foreach ($brand_management as $brand) {
                $reports[] = [
                    'id' => $brand->id,
                    'category_id' => $brand->category_id,
                    'category_name' => $brand->category_name,
                    'name' => $brand->name,
                    'descriptions' => $brand->descriptions,
                    'logo' => ($brand->logo != "") ? URL::to("/" . $brand->logo) : " ",
                    'active' => $brand->active,
                    'created_at' => $brand->created_at,
                    'updated_at' => $brand->updated_at,
                    'deleted_at' => $brand->deleted_at,
                    'supplementals' => $brand->supplemental_names,
                    'tags' => $brand->tag_names,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "brand_management_".(substr($ids, 0, 1)+1).".csv";
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
                'category_id' => '',
                'category_name' => '',
                'name' => '',
                'descriptions' => '',
                'logo' => '',
                'active' => '',
                'created_at' => '',
                'updated_at' => '',
                'deleted_at' => '',
                'supplementals' => '',
                'tags' => '',
            ];

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "brand-managemet-template.csv";
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

    public function countBrands()
    {
        try {
            $brands = Brand::get()->count();
            return $this->response($brands, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}
