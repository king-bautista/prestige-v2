<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\UsersInformationControllerInterface;
use Illuminate\Http\Request;

use App\Helpers\PasswordHelper;
use App\Models\Admin;
use App\Models\ViewModels\AdminViewModel;
use Hash;

class UsersInformationController extends AppBaseController implements UsersInformationControllerInterface
{
    /************************************
    * 			USERS MANAGEMENT		*
    ************************************/
    public function __construct()
    {
        $this->module_id = 72; 
        $this->module_name = 'Users Information';
    }

    public function index()
    {  
       return view('admin.users_information');
    }
}
