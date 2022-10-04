<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\MapsControllerInterface;
use Illuminate\Http\Request;

use App\Models\Amenity;
use App\Models\SitePoint;
use App\Models\ViewModels\SiteViewModel;
use App\Models\ViewModels\SiteMapViewModel;
use App\Models\ViewModels\SiteTenantViewModel;

class MapsController extends AppBaseController implements MapsControllerInterface
{
    /********************************
    * 			MAP MANAGEMENT	 	*
    ********************************/
    public function __construct()
    {
        $this->module_id = 13; 
        $this->module_name = 'Sites Management';
    }

    public function getMapDetails($floor_id)
    {
        $site_id = session()->get('site_id');
        $site_details = SiteViewModel::find($site_id);
        $site_maps = SiteMapViewModel::where('site_id', $site_id)->get();
        $current_map = SiteMapViewModel::where('site_building_level_id', $floor_id)->first();
        $amenities = Amenity::get();
        $site_tenants = SiteTenantViewModel::where('site_building_level_id', $floor_id)->get();
        
        return view('admin.map', compact(['site_details', 'site_maps', 'current_map', 'amenities', 'site_tenants']));
    }

    public function createPoint(Request $request)
    {
        try
    	{
            $data = [
                'site_map_id' => $request->map_id,
                'point_x' => $request->point_x,
                'point_y' => $request->point_y,
            ];

            $site_point = SitePoint::create($data);

            return $this->response($site_point, 'Successfully Created!', 200);
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
