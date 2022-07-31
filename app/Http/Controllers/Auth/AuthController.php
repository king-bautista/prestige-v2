<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Auth\Interfaces\AuthControllerInterface;
use Illuminate\Http\Request;
use App\Helpers\PasswordHelper;
use App\Http\Requests\RegistrationRequest;
use App\Models\Admin;

class AuthController extends AppBaseController implements AuthControllerInterface
{
    /************************************
    * 			AUTHENTICATION			*
    ************************************/
    public function register(RegistrationRequest $request)
    {
        $salt = bcrypt(PasswordHelper::generateSalt());
        $password = PasswordHelper::generatePassword($salt, $request->password);

        $data = [
            'full_name' => $request->last_name.', '.$request->first_name,
            'email' => $request->email,
            'salt' => $salt,
            'password' => $password,
            'active' => 1
        ];

        $admin_user = Admin::create($data);

        // save meta data

        // save user roles

        // if api request return json data here
        if ($request->is('api/*')) {
            return $this->response($admin_user, 'Successfully Created!', 200);
        }
        // return view or redirection here

    }

    public function login(Request $request)
    {
        # code...
    }
}
