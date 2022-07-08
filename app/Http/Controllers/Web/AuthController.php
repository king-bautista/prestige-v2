<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController as AppBaseController;
use App\Http\Controllers\Web\Interfaces\AuthControllerInterface;
use App\Helpers\PasswordHelper;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\User;
use Hash;

class AuthController extends AppBaseController implements AuthControllerInterface
{
    public function registerUser(Request $request)
    {
        # code...
    }
}
