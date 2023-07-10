<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\SiteScreenProductControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Requests\SiteScreenProductRequest;

use App\Models\SiteScreenProduct;
use App\Models\ContractScreen;
use App\Models\ViewModels\SiteScreenProductViewModel;
use App\Exports\Export;
use Storage;

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
                return $query->where('site_screens.site_point_id', 'LIKE', '%' . request('search') . '%')
                ->orWhere('site_screens.screen_type', 'LIKE', '%' . request('search') . '%')
                ->orWhere('site_screens.orientation', 'LIKE', '%' . request('search') . '%')
                ->orWhere('site_screens.product_application', 'LIKE', '%' . request('search') . '%')
                ->orWhere('site_screens.name', 'LIKE', '%' . request('search') . '%')
                ->orWhere('site_buildings.name', 'LIKE', '%' . request('search') . '%')
                ->orWhere('site_building_levels.name', 'LIKE', '%' . request('search') . '%')
                ->orWhere('sites.name', 'LIKE', '%' . request('search') . '%');
            })
            ->when(count($site_ids) > 0, function ($query) use ($site_ids) {
                return $query->whereIn('site_screens.site_id', $site_ids);
            })
            ->leftJoin('site_screens', 'site_screen_products.site_screen_id', '=', 'site_screens.id')
            ->leftJoin('sites', 'site_screens.site_id', '=', 'sites.id')
            ->leftJoin('site_buildings', 'site_screens.site_building_id', '=', 'site_buildings.id')
            ->leftJoin('site_building_levels', 'site_screens.site_building_level_id', '=', 'site_building_levels.id')
            ->select('site_screen_products.*')
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
                'description' => $request->description,
                'dimension' => $request->width.'x'.$request->height,
                'width' => $request->width,
                'height' => $request->height,
                'sec_slot' => $request->sec_slot,
                'slots' => $request->slots,
                'active' => $request->active,
                'is_exclusive' => $request->is_exclusive,
            ];

            $site_screen_product = SiteScreenProduct::create($data);
            $site_screen_product->serial_number = 'SSP-'.Str::padLeft($site_screen_product->id, 5, '0');
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
                'serial_number' => ($site_screen_product->serial_number) ? $site_screen_product->serial_number : 'SSP-'.Str::padLeft($site_screen_product->id, 5, '0'),
                'site_screen_id' => $request->site_screen_id['id'],
                'ad_type' => $request->ad_type['ad_type'],
                'description' => $request->description,
                'dimension' => $request->width.'x'.$request->height,
                'width' => $request->width,
                'height' => $request->height,
                'sec_slot' => $request->sec_slot,
                'slots' => $request->slots,
                'active' => $request->active,
                'is_exclusive' => $request->is_exclusive,
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
            return 'test';
            return $request->all();

            $site_screen_products = [];

            $site_screen_ids = ContractScreen::where('contract_id', $request->contract_id)->where('site_screen_id', '>', 0)->get()->pluck('site_screen_id');
            if(count($site_screen_ids)) {
                return $site_screen_ids;
            }

            $site_ids = ContractScreen::where('contract_id', $request->contract_id)->where('site_id', '>', 0)->groupBy('site_id')->get()->pluck('site_id');
            if(count($site_ids)) {
                $site_screen_products = SiteScreenProductViewModel::whereIn('site_screens.site_id', $site_ids)
                ->where('site_screen_products.width', $request->width)
                ->where('site_screen_products.height', $request->height)
                ->leftJoin('site_screens', 'site_screen_products.site_screen_id', '=', 'site_screens.id')
                ->leftJoin('sites', 'site_screens.site_id', '=', 'sites.id')
                ->leftJoin('site_buildings', 'site_screens.site_building_id', '=', 'site_buildings.id')
                ->leftJoin('site_building_levels', 'site_screens.site_building_level_id', '=', 'site_building_levels.id')
                ->select('site_screen_products.*')
                ->get();
            }

            $all_sites = ContractScreen::where('contract_id', $request->contract_id)->where('product_application', '=', 'All')->first();
            if($all_sites) {
                $site_screen_products = SiteScreenProductViewModel::where('site_screen_products.width', $request->width)
                ->where('site_screen_products.height', $request->height)
                ->leftJoin('site_screens', 'site_screen_products.site_screen_id', '=', 'site_screens.id')
                ->leftJoin('sites', 'site_screens.site_id', '=', 'sites.id')
                ->leftJoin('site_buildings', 'site_screens.site_building_id', '=', 'site_buildings.id')
                ->leftJoin('site_building_levels', 'site_screens.site_building_level_id', '=', 'site_building_levels.id')
                ->select('site_screen_products.*')
                ->get();
            }

            return $this->response($site_screen_products, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

}
