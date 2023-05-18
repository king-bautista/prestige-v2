<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\PiProductControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Requests\PiProductRequest;

use App\Models\PiProduct;
use App\Models\ContractScreen;
use App\Models\ViewModels\PiProductViewModel;
use App\Exports\Export;
use Storage;

class PiProductController extends AppBaseController implements PiProductControllerInterface
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
        return view('admin.pi_products');
    }

    public function list(Request $request)
    {
        try {
            $filters = json_decode($request->filters);
            $site_ids = [];
            if ($filters)
                $site_ids = $filters->site_ids;

            $pi_products = PiProductViewModel::when(request('search'), function ($query) {
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
            ->leftJoin('site_screens', 'pi_products.site_screen_id', '=', 'site_screens.id')
            ->leftJoin('sites', 'site_screens.site_id', '=', 'sites.id')
            ->leftJoin('site_buildings', 'site_screens.site_building_id', '=', 'site_buildings.id')
            ->leftJoin('site_building_levels', 'site_screens.site_building_level_id', '=', 'site_building_levels.id')
            ->select('pi_products.*')
            ->latest()
            ->paginate(request('perPage'));

            return $this->responsePaginate($pi_products, 'Successfully Retreived!', 200);
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
            $pi_product = PiProductViewModel::find($id);
            return $this->response($pi_product, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(PiProductRequest $request)
    {
        try {
            $data = [
                'site_screen_id' => $request->site_screen_id['id'],
                'ad_type' => $request->ad_type,
                'description' => $request->description,
                'dimension' => $request->width.'x'.$request->height,
                'width' => $request->width,
                'height' => $request->height,
                'sec_slot' => $request->sec_slot,
                'slots' => $request->slots,
                'active' => 1,
            ];

            $pi_product = PiProduct::create($data);
            return $this->response($pi_product, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(PiProductRequest $request)
    {
        try {
            $pi_product = PiProduct::find($request->id);

            $data = [
                'site_screen_id' => $request->site_screen_id['id'],
                'ad_type' => $request->ad_type,
                'description' => $request->description,
                'dimension' => $request->width.'x'.$request->height,
                'width' => $request->width,
                'height' => $request->height,
                'sec_slot' => $request->sec_slot,
                'slots' => $request->slots,
                'active' => $request->active,
            ];

            $pi_product->update($data);

            return $this->response($pi_product, 'Successfully Modified!', 200);
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
            $pi_product = PiProduct::find($id);
            $pi_product->delete();
            return $this->response($pi_product, 'Successfully Deleted!', 200);
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
            $pi_products = [];

            $site_screen_ids = ContractScreen::where('contract_id', $request->contract_id)->where('site_screen_id', '>', 0)->get()->pluck('site_screen_id');
            if(count($site_screen_ids)) {
                return $site_screen_ids;
            }

            $site_ids = ContractScreen::where('contract_id', $request->contract_id)->where('site_id', '>', 0)->groupBy('site_id')->get()->pluck('site_id');
            if(count($site_ids)) {
                $pi_products = PiProductViewModel::whereIn('site_screens.site_id', $site_ids)
                ->where('pi_products.width', $request->width)
                ->where('pi_products.height', $request->height)
                ->leftJoin('site_screens', 'pi_products.site_screen_id', '=', 'site_screens.id')
                ->leftJoin('sites', 'site_screens.site_id', '=', 'sites.id')
                ->leftJoin('site_buildings', 'site_screens.site_building_id', '=', 'site_buildings.id')
                ->leftJoin('site_building_levels', 'site_screens.site_building_level_id', '=', 'site_building_levels.id')
                ->select('pi_products.*')
                ->get();
            }

            $all_sites = ContractScreen::where('contract_id', $request->contract_id)->where('product_application', '=', 'All')->first();
            if($all_sites) {
                $pi_products = PiProductViewModel::where('pi_products.width', $request->width)
                ->where('pi_products.height', $request->height)
                ->leftJoin('site_screens', 'pi_products.site_screen_id', '=', 'site_screens.id')
                ->leftJoin('sites', 'site_screens.site_id', '=', 'sites.id')
                ->leftJoin('site_buildings', 'site_screens.site_building_id', '=', 'site_buildings.id')
                ->leftJoin('site_building_levels', 'site_screens.site_building_level_id', '=', 'site_building_levels.id')
                ->select('pi_products.*')
                ->get();
            }

            return $this->response($pi_products, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

}
