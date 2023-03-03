<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ClientUserControllerInterface;
use Illuminate\Http\Request;
use App\Http\Requests\ClientUserRequest;

use App\Helpers\PasswordHelper;
use Hash;

class ClientUserController extends AppBaseController implements ClientUserControllerInterface
{
    /****************************************
    * 			CLIENT USERS MANAGEMENT		*
    ****************************************/
    public function __construct()
    {
        $this->module_id = 46; 
        $this->module_name = 'User Management';
    }

    public function index()
    {
        return view('admin.client_users');
    }
}
