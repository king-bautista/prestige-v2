<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

use App\Models\ViewModels\SiteViewModel;

class MainController extends AppBaseController
{
    public function index()
    {
        $site = SiteViewModel::where('is_default', 1)->first();
        return view('kiosk.main', compact('site'));
    }
}
