<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\SiteScreenProductControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\SiteScreenProductRequest;
use App\Imports\SiteScreenProductsImport;
use Storage;

use App\Models\AdminViewModels\SiteScreenProductViewModel;
use App\Models\AdminViewModels\SiteScreenViewModel;
use App\Models\SiteScreenProduct;
use App\Models\ContractScreen;
use App\Exports\Export;

class SiteScreenProductController extends AppBaseController implements SiteScreenProductControllerInterface
{
    /********************************************
     * 			SITES SCREENS MANAGEMENT	 	*
     ********************************************/
    public function __construct()
    {
        $this->module_id = 75;
        $this->module_name = 'Site Screen Product';
    }

    public function index()
    {
        return view('admin.site_screen_products');
    }

    public function list(Request $request)
    { 
        try {
            $filters = json_decode($request->filters);
            $site_ids = [];
            if ($filters)
                $site_ids = $filters->site_ids;

            $site_screen_products = SiteScreenProductViewModel::when(request('search'), function ($query) {
                return $query->where('site_screen_products.serial_number', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('site_screens.screen_type', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('site_screens.orientation', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('site_screens.product_application', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('site_screen_products.ad_type', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('site_screen_products.dimension', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('site_screens.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('site_buildings.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('site_building_levels.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('site_screens.serial_number', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('sites.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhereRaw('CONCAT(`sites_meta`.`meta_value`,\' - \',`site_screens`.`name`,\', \',`site_buildings`.`name`,\', \',`site_building_levels`.`name`,\' (\',`site_screen_products`.`ad_type`,\' / \',`site_screen_products`.`dimension`,\')\') LIKE \'%' . request('search') . '%\'');
            })
                ->when(count($site_ids) > 0, function ($query) use ($site_ids) {
                    return $query->whereIn('site_screens.site_id', $site_ids);
                })
                ->leftJoin('site_screens', 'site_screen_products.site_screen_id', '=', 'site_screens.id')
                ->leftJoin('sites', 'site_screens.site_id', '=', 'sites.id')
                ->leftJoin('site_buildings', 'site_screens.site_building_id', '=', 'site_buildings.id')
                ->leftJoin('site_building_levels', 'site_screens.site_building_level_id', '=', 'site_building_levels.id')
                ->leftJoin('sites_meta', function ($join) {
                    $join->on('sites.id', '=', 'sites_meta.site_id')
                        ->where('sites_meta.meta_key', '=', 'site_code');
                })
                ->select('site_screen_products.*')
            //    ->selectRaw('CONCAT(JSON_OBJECT("meta_value", sites_meta.meta_key),',',site_screens.name,',',site_buildings.name,',',site_building_levels.name,',',site_screen_products.ad_type) AS screen_location')
                //->selectRaw('CONCAT(site_screens.name,',',site_buildings.name,',',site_building_levels.name,',',site_screen_products.ad_type) AS screen_location')
                ->selectRaw("CONCAT(site_screens.name,site_buildings.name,site_building_levels.name) AS site_screen_location")
                ->when(request('order'), function ($query) {
                    $column = $this->checkcolumn(request('order'));
                    switch ($column) {
                        case 'site_screen_location':
                            $field = 'site_screen_location';
                            break;
                        default:
                            $field = $column;
                    }
                    
                    return $query->orderBy($field, request('sort'));
                })
                ->latest()
                ->paginate(request('perPage'));

            return $this->responsePaginate($site_screen_products, 'Successfully Retreived!', 200);
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
            $site_screen_product = SiteScreenProductViewModel::find($id);
            return $this->response($site_screen_product, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(SiteScreenProductRequest $request)
    {
        try {
            $data = [
                'site_screen_id' => $request->site_screen_id['id'],
                'ad_type' => $request->ad_type['ad_type'],
                'description' => ($request->description) ? $request->description : null,
                'dimension' => $request->width . 'x' . $request->height,
                'width' => $request->width,
                'height' => $request->height,
                'sec_slot' => $request->sec_slot,
                'slots' => $request->slots,
                'active' => $this->checkBolean($request->active),
                'is_exclusive' => $this->checkBolean($request->is_exclusive),
            ];

            $site_screen_product = SiteScreenProduct::create($data);
            $site_screen_product->serial_number = 'SSP-' . Str::padLeft($site_screen_product->id, 5, '0');
            $site_screen_product->save();

            return $this->response($site_screen_product, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(SiteScreenProductRequest $request)
    {
        try {
            $site_screen_product = SiteScreenProduct::find($request->id);

            $data = [
                'serial_number' => ($site_screen_product->serial_number) ? $site_screen_product->serial_number : 'SSP-' . Str::padLeft($site_screen_product->id, 5, '0'),
                'site_screen_id' => $request->site_screen_id['id'],
                'ad_type' => $request->ad_type['ad_type'],
                'description' => ($request->description) ? $request->description : null,
                'dimension' => $request->width . 'x' . $request->height,
                'width' => $request->width,
                'height' => $request->height,
                'sec_slot' => $request->sec_slot,
                'slots' => $request->slots,
                'active' => $this->checkBolean($request->active),
                'is_exclusive' => $this->checkBolean($request->is_exclusive),
            ];

            $site_screen_product->update($data);

            return $this->response($site_screen_product, 'Successfully Modified!', 200);
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
            $site_screen_product = SiteScreenProduct::find($id);
            $site_screen_product->delete();
            return $this->response($site_screen_product, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getScreen(Request $request)
    {
        try {
            $contract_screen = ContractScreen::where('contract_id', $request->contract_id)->first();
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

            if ($contract_screen->product_application == 'All')
                $site_screens[] = [
                    'id' => 0,
                    'site_id' => 0,
                    'site_screen_location' => 'All (Sites screens)',
                    'product_application' => 'All'
                ];

            return $this->response($site_screens, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getScreenSize(Request $request)
    {
        try {
            $is_all = false;
            $site_ids = [];
            $site_screen_id = [];
            foreach ($request->all() as $key => $value) {
                if ($value['site_id'] && $value['id'] == 0)
                    $site_ids[] = $value['site_id'];

                if ($value['id'] > 0)
                    $site_screen_id[] = $value['id'];

                if ($value['product_application'] == 'All')
                    $is_all = true;
            }

            $screen_sizes = SiteScreenProductViewModel::when(!$is_all, function ($query) use ($site_ids, $site_screen_id) {
                $query->whereIn('site_screens.site_id', $site_ids)
                    ->orWhereIn('site_screen_products.site_screen_id', $site_screen_id);
            })
                ->join('site_screens', 'site_screen_products.site_screen_id', '=', 'site_screens.id')
                ->select('site_screen_products.dimension')
                ->groupBy('site_screen_products.dimension')
                ->get();

            return $this->response($screen_sizes, 'Successfully Retreived!', 200);
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
            Excel::import(new SiteScreenProductsImport, $request->file('file'));
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

            $ssps =  SiteScreenProductViewModel::get();
            $reports = [];
            foreach ($ssps as $ssp) {
                $reports[] = [
                    'id' => $ssp->id,
                    'serial_number' => $ssp->serial_number,
                    'site_screen_id' => $ssp->site_screen_id,
                    'site_screen_name' => $ssp->site_screen_details['name'],
                    'ad_type' => $ssp->ad_type,
                    'description' => $ssp->description,
                    'dimension' => $ssp->dimension,
                    'width' => $ssp->width,
                    'height' => $ssp->height,
                    'sec_slot' => $ssp->sec_slot,
                    'slots' => $ssp->slots,
                    'is_exclusive' => $ssp->is_exclusive,
                    'active' => $ssp->active,
                    'created_at' => $ssp->created_at,
                    'updated_at' => $ssp->updated_at,
                    'deleted_at' => $ssp->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "site-screen-products.csv";
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
                'serial_number' => '',
                'site_screen_id' => '',
                'site_screen_name' => '',
                'ad_type' => '',
                'description' => '',
                'dimension' => '',
                'width' => '',
                'height' => '',
                'sec_slot' => '',
                'slots' => '',
                'is_exclusive' => '',
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

            $filename = "site-screen-products-template.csv";
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
