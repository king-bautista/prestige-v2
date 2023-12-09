<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\AdminViewModels\SiteViewModel;


class KiskController extends AppBaseController
{
    //
    public function index($site_name = null)
    {
        $site = SiteViewModel::when(!$site_name, function($query) {
            $query->where('is_default', 1);
        })
        ->when($site_name, function($query) use($site_name) {
            $query->whereRaw('REPLACE(LOWER(name), " ", "_") = ?', [$site_name]);
        })
        ->where('active', 1)->first(); 

        if(!$site)
            return view('kiosk.page-not-found');
        return view('kiosk.sm_tanza.main');

        // GET PLAYLIST

        // GET CATEGORIES
            // GET SUB-CATEGORIES
            // GET TENANTS PER SUB-CATEGORIES
            // GET ALPHABETICAL TENANTS
            // GET SUPLEMENTALS
            // GET TENANTS BY SUPPLEMENTALS

        // MAP



    }

}
