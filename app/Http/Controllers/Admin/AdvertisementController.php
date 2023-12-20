<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\AdvertisementControllerInterface;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\AdvertisementRequest;
use App\Exports\Export;
use Storage;

use App\Models\Advertisement;
use App\Models\AdvertisementMaterial;
use App\Models\AdvertisementScreen;
use App\Models\AdminViewModels\AdvertisementViewModel;
use App\Models\AdminViewModels\AdvertisementMaterialViewModel;
//use App\Models\AdminViewModels\ContentMaterialViewModel;

use App\Models\AdminViewModels\SiteScreenViewModel;
use App\Models\ContractScreen;

class AdvertisementController extends AppBaseController implements AdvertisementControllerInterface
{
    /************************************************
     * 			ADVERTISEMENT ADS MANAGEMENT	 	*
     ************************************************/
    public function __construct()
    {
        $this->module_id = 14;
        $this->module_name = 'Advertisements';
    }

    public function index()
    {
        return view('admin.advertisements_online');
    }

    public function list(Request $request)
    {
        try {
            $advertisements = AdvertisementViewModel::when(request('search'), function ($query) {
                return $query->where('advertisements.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('brands.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('companies.name', 'LIKE', '%' . request('search') . '%');
            })
                ->leftJoin('brands', 'advertisements.brand_id', '=', 'brands.id')
                ->leftJoin('companies', 'advertisements.company_id', '=', 'companies.id')
                ->select('advertisements.*')
                ->orderBy('advertisements.created_at', 'DESC')
                ->paginate(request('perPage'));
            return $this->responsePaginate($advertisements, 'Successfully Retreived!', 200);
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
            $advertisement = AdvertisementViewModel::find($id);
            return $this->response($advertisement, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(AdvertisementRequest $request)
    {
        try {
            $materials = json_decode($request->materials);
            $count = 0;
            foreach ($materials as $index => $material) {
                if ($material->src)
                    $count++;
            }
            if (!$count) {
                return response([
                    'message' => 'material is required.',
                    'status' => false,
                    'status_code' => 422,
                ], 422);
            }

            $company_id = json_decode($request->company_id);
            $contract_id = json_decode($request->contract_id);
            $brand_id = json_decode($request->brand_id);

            $data = [
                'name' => $request->name,
                'company_id' => ($company_id) ? $company_id->id : null,
                'contract_id' => ($contract_id) ? $contract_id->id : null,
                'brand_id' => ($brand_id) ? $brand_id->id : null,
                'display_duration' => $request->display_duration,
                'active' => ($request->active == 'false') ? 0 : 1
            ];

            $advertisement = Advertisement::create($data);
            $advertisement->serial_number = 'AD-' . Str::padLeft($advertisement->id, 5, '0');
            $advertisement->save();
            $advertisement->saveMaterials(json_decode($request->materials), $request->file('files'));

            return $this->response($advertisement, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(AdvertisementRequest $request)
    {
        try {
            $materials = json_decode($request->materials);
            if (!$materials[0]->src || $materials[0]->src == '') {
                return response([
                    'message' => 'material is required.',
                    'status' => false,
                    'status_code' => 422,
                ], 422);
            }

            $advertisement = Advertisement::find($request->id);
            $advertisement->touch();

            $company_id = json_decode($request->company_id);
            $contract_id = json_decode($request->contract_id);
            $brand_id = json_decode($request->brand_id);
            // $status_id = json_decode($request->status_id);

            $data = [
                'serial_number' => ($advertisement->serial_number) ? $advertisement->serial_number : 'AD-' . Str::padLeft($advertisement->id, 5, '0'),
                'company_id' => ($company_id) ? $company_id->id : null,
                'contract_id' => ($contract_id) ? $contract_id->id : null,
                'brand_id' => ($brand_id) ? $brand_id->id : null,
                'status_id' => 5,
                'product_application' => $request->product_application,
                'display_duration' => $request->display_duration,
                'name' => $request->name,
                'active' => ($request->active == 'false') ? 0 : 1
            ];

            $advertisement->update($data);
            $advertisement->saveMaterials(json_decode($request->materials), $request->file('files'));

            return $this->response($advertisement, 'Successfully Modified!', 200);
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
            $advertisement = Advertisement::find($id);
            $advertisement->delete();
            return $this->response($advertisement, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getAllType(Request $request)
    {
        try {
            $advertisements = ContentMaterialViewModel::when(request('search'), function ($query) {
                return $query->where('advertisements.name', 'LIKE', '%' . request('search') . '%')
                    ->where('companies.name', 'LIKE', '%' . request('search') . '%')
                    ->where('brands.name', 'LIKE', '%' . request('search') . '%');
            })
                ->join('advertisements', 'advertisement_materials.advertisement_id', '=', 'advertisements.id')
                ->leftJoin('companies', 'advertisements.company_id', '=', 'companies.id')
                ->leftJoin('brands', 'advertisements.brand_id', '=', 'brands.id')
                ->select('advertisement_materials.*')
                ->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($advertisements, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getMaterialDetails($id)
    {
        try {
            $material = ContentMaterialViewModel::find($id);
            return $this->response($material, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function deleteMaterial($id)
    {
        try {
            $screens = AdvertisementScreen::where('material_id', $id)->count();
            if ($screens > 0) {
                return response([
                    'message' => "You can't this delete material, it's been assigned to a screen/s. ",
                    'status' => false,
                    'status_code' => 422,
                ], 422);
            }

            $material = AdvertisementMaterial::find($id);
            $material->delete();

            return $this->response($material, 'Successfully Deleted!.', 200);
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

            $create_contents = AdvertisementViewModel::get();
            $reports = [];
            foreach ($create_contents as $create_content) {
                $reports[] = [
                    'id' => $create_content->id,
                    'material_thumbnails_path' => $create_content->material_thumbnails_path,
                    'name' => $create_content->name,
                    'serial_number' => $create_content->serial_number,
                    'company_id' => $create_content->company_id,
                    'company_name' => $create_content->company_name,
                    'contract_id' => $create_content->contract_id,
                    'contract_name' => $create_content->contract_details['name'],
                    'brand_id' => $create_content->brand_id,
                    'brand_name' => $create_content->brand_name,
                    'display_duration' => $create_content->display_duration,
                    'ssp' =>  $this->getSSP($create_content->contract_id),
                    'active' => $create_content->active,
                    'created_at' => $create_content->created_at,
                    'updated_at' => $create_content->updated_at,
                    'deleted_at' => $create_content->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "create-content-manage-ads.csv";
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
                'material_thumbnails_path' => '',
                'name' => '',
                'serial_number' => '',
                'company_id' => '',
                'company_name' => '',
                'contract_id' => '',
                'contract_name' => '',
                'brand_id' => '',
                'brand_name' => '',
                'display_duration' => '',
                'ssp' =>  '',
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

            $filename = "create-content-manage-ads.csv";
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

    public function getSSP($id)
    {

        $contract_screen = ContractScreen::where('contract_id', $id)->first();
        $site_screens_data = SiteScreenViewModel::when($contract_screen->site_screen_id > 0, function ($query) use ($contract_screen) {
            return $query->where('site_screens.id', $contract_screen->site_screen_id);
        })
            ->when($contract_screen->site_id > 0, function ($query) use ($contract_screen) {
                return $query->where('site_screens.site_id', $contract_screen->site_id);
            })
            ->when($contract_screen->product_application != 'All', function ($query) use ($contract_screen) {
                return $query->where('site_screens.product_application', $contract_screen->product_application);
            })
            ->select('site_screens.*');

        $site_screens = $site_screens_data->get();

        $site_all_directory = $site_screens_data->groupBy('site_screens.site_id', 'site_screens.product_application')->get();
        if ($site_all_directory) {
            foreach ($site_all_directory as $directory) {
                $site_screens[] = [
                    'id' => 0,
                    'site_id' => $directory->site_id,
                    'site_screen_location' => $directory->site_code_name . ' - All (' . $directory->product_application . ')',
                    'product_application' => $directory->product_application
                ];
            }
        }

        if ($contract_screen->product_application == 'All') {
            $site_screens[] = [
                'id' => 0,
                'site_id' => 0,
                'site_screen_location' => 'All (Sites screens)',
                'product_application' => 'All'
            ];
        }

        if (count($site_screens) > 0) {
            $ssp = [];
            foreach ($site_screens as $site_screen) {
                $ssp[] = $site_screen['site_screen_location'];
            }

            return implode("#", $ssp);
        }
        return null;
    }
}
