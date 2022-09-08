<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Api\Interfaces\AuthControllerInterface;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;

use App\Helpers\PasswordHelper;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use Hash;

class AuthController extends AppBaseController implements AuthControllerInterface
{
    /************************************
    * 			AUTHENTICATION			*
    ************************************/
    public function register(RegistrationRequest $request)
    {
        try
    	{
            $salt = PasswordHelper::generateSalt();
            $password = PasswordHelper::generatePassword($salt, $request->password);
            $data = [
                'full_name' => $request->last_name.', '.$request->first_name,
                'email' => $request->email,
                'salt' => $salt,
                'password' => $password,
                'active' => 1
            ];

            $admin_user = Admin::create($data);

            $meta_details = ["first_name" => $request->first_name, "last_name" => $request->last_name];
            $admin_user->saveMeta($meta_details);

            // save user roles

            // if api request return json data here
            if ($request->is('api/*')) {
                return $this->response($admin_user, 'Successfully Created!', 200);
            }
            // return view or redirection here
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }

    }

    public function login(LoginRequest $request)
    {
        try
    	{
            $admin_user = Admin::where('email', '=', $request->email)->where('active', true)->first();
            if (!Hash::check($admin_user->salt.env("PEPPER_HASH").$request->password, $admin_user->password))
    			return response([
                    'message' => 'Invalid email or password.',
                    'status' => false,
                    'status_code' => 401,
                ], 401);            

            // if api request return json data here
            if ($request->is('api/*')) {
                
                Auth::login($admin_user);

                $tokenResult = $admin_user->createToken(env('APP_NAME'));
                
                $data = [
                    'token' => $tokenResult->plainTextToken,
                    'token_type' => 'Bearer',
                    'user'  => $admin_user,
                ];
                
                return $this->response($data, 'Successfully Loged!', 200);
            }
            // return view or redirection here

        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }
}
