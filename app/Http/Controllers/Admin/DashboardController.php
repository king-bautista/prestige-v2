<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends AppBaseController
{
    /************************************
    * 	    DASHBOARD MANAGEMENT		*
    ************************************/    
    public function index()
    {
        //return $user = Auth::user();
        return view('admin.dashboard');
    }
}
