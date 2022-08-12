<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\ViewModels\AdminViewModel;

class DashboardController extends AppBaseController
{
    /************************************
    * 	    DASHBOARD MANAGEMENT		*
    ************************************/    
    public function index()
    {
        //return $user = AdminViewModel::find(Auth::user()->id);
        return view('admin.dashboard');
    }
}
