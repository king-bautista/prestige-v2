<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\SiteMapControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Requests\ScreenRequest;

use App\Models\SiteScreen;
use App\Models\AdminViewModels\SiteScreenViewModel;
use App\Exports\Export;
use Storage;

class SiteMapController extends AppBaseController implements SiteMapControllerInterface
{
    /********************************************
     * 			SITES SCREENS MANAGEMENT	 	*
     ********************************************/
    public function __construct()
    {
        $this->module_id = 76;
        $this->module_name = 'Maps';
    }

    public function index()
    {
        return view('admin.site_maps');
    }

    public function list(Request $request)
    {
        try {
            $filters = json_decode($request->filters);
            $site_ids = [];
            if ($filters)
                $site_ids = $filters->site_ids;

            $site_screens = SiteScreenViewModel::when(request('search'), function ($query) {
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
            ->where('site_screens.product_application', 'Directory')
            ->leftJoin('sites', 'site_screens.site_id', '=', 'sites.id')
            ->leftJoin('site_buildings', 'site_screens.site_building_id', '=', 'site_buildings.id')
            ->leftJoin('site_building_levels', 'site_screens.site_building_level_id', '=', 'site_building_levels.id')
            ->select('site_screens.*')
            ->latest()
            ->paginate(request('perPage'));

            return $this->responsePaginate($site_screens, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}
