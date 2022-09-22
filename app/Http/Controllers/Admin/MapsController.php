<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\MapsControllerInterface;
use Illuminate\Http\Request;

use App\Models\ViewModels\SiteViewModel;
use App\Models\ViewModels\SiteMapViewModel;

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

        return view('admin.map', compact(['site_details', 'site_maps', 'current_map']));
    }

}
