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

use App\Models\Company;
use App\Models\Tag;
use App\Models\Brand;

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
            $host = $request->getSchemeAndHttpHost();
            $brands = Brand::when(request('search'), function ($query) {
                return $query->having('name', 'LIKE', '%' . request('search') . '%')
                    ->orHaving('category_name', 'LIKE', '%' . request('search') . '%')
                    ->orHaving('supplemental_names', 'LIKE', '%' . request('search') . '%')
                    ->orHaving('tag_names', 'LIKE', '%' . request('search') . '%')
                    ;
            })
                ->when(count($brand_ids) > 0, function ($query) use ($brand_ids) {
                    return $query->whereIn('brands.id', $brand_ids);
                })
                ->select('brands.id')
                ->selectRaw('brands.id as brand_id, 
                brands.name as name,
                CONCAT("' . $host . '/",`logo`) as logo_image_path, 
                (select c.name from categories as c where brands.category_id = c.id ) as category_name,
                (select group_concat(c.name) as name from categories c left join brand_supplementals bs on bs.supplemental_id = c.id where bs.brand_id = brands.id) as supplemental_names,
                (select group_concat(t.name) as name from tags t left join brand_tags bt on bt.tag_id = t.id where bt.brand_id = brands.id) as tag_names,
                brands.active as active, brands.updated_at as updated_at')
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('name', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
                ->groupBy('brand_id')
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
                //'name' =>   addslashes(strtoupper($request->name)),
                'name' =>  $request->name,
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
               // 'name' => addslashes(strtoupper($request->name)),
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
            $brand_management =  BrandViewModel::skip($between[0])->take(100)->get();

            $reports = [];
            foreach ($brand_management as $brand) {
                $company_brand = CompanyBrands::where("brand_id", $brand->id)->get();
                $company_id = (count($company_brand) > 0) ? $company_brand[0]['company_id'] : 0;
                if ($company_id != 0) {
                    $company = Company::where("id", $company_id)->get();
                    $company_name = (count($company) > 0) ? $company_name = $company[0]['name'] : '';
                } else {
                    $company_name = '';
                }

                $reports[] = [
                    'id' => $brand->id,
                    'category_id' => $brand->category_id,
                    'category_name' => $brand->brand_details['category_name'],
                    'sub_category_id' => $brand->brand_details['parent_category_id'],
                    'sub_category_name' => $brand->brand_details['parent_category_name'],
                    'supplemental_category_id' => $brand->supplemental_ids,
                    'supplemental_category_name' => $brand->supplemental_names,
                    'name' => $brand->name,
                    'descriptions' => $brand->descriptions,
                    'logo' => ($brand->logo != "") ? URL::to("/" . $brand->logo) : " ",
                    'company_id' => $company_id,
                    'company_name' => $company_name,
                    'brand_id' => $brand->id,
                    'tag_ids' => $brand->tag_ids,
                    'tags' => $brand->tag_names,
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

            $filename = "brand_management_" . (substr($ids, 0, 1) + 1) . ".csv";
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
                'sub_category_id' => '',
                'sub_category_name' => '',
                'supplemental_category_id' => '',
                'supplemental_category_name' => '',
                'name' => '',
                'descriptions' => '',
                'logo' => '',
                'company_id' => '',
                'company_name' => '',
                'brand_id' => '',
                'tag_ids' => '',
                'tags' => '',
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
