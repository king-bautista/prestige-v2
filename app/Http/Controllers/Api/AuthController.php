<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Api\Interfaces\AuthControllerInterface;
use Illuminate\Http\Request;
use App\Helpers\PasswordHelper;

class AuthController extends AppBaseController implements AuthControllerInterface
{
    /************************************
    * 			AUTHENTICATION			*
    ************************************/
    public function register(Request $request)
    {
        # code...
    }
}
