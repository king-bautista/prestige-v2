<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\ViewModels\UserViewModel;

class DashboardController extends AppBaseController
{
    /************************************
    * 	    DASHBOARD MANAGEMENT		*
    ************************************/    
    public function index()
    {
        //return $user = AdminViewModel::find(Auth::user()->id);
        
        //return view('portal.dashboard');
        return view('portal.testdashboard');
    }
}
