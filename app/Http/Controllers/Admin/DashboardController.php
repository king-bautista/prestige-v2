<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

class DashboardController extends AppBaseController
{
    /************************************
    * 	    DASHBOARD MANAGEMENT		*
    ************************************/    
    public function index()
    {
        return view('admin.dashboard');
    }
}
