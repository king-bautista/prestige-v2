<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\AdminAuth\Interfaces\LoginControllerInterface;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

use App\Models\Admin;
use Session;
use Hash;

class AuthController extends AppBaseController implements LoginControllerInterface
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirect_to = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    public function login()
    {
        return view('admin.login');
    }

    public function adminLogin(LoginRequest $request)
    {
        try
        {
            $admin_user = Admin::where('email', '=', $request->email)->where('active', true)->first();
            if ($admin_user && Hash::check($admin_user->salt.env("PEPPER_HASH").$request->password, $admin_user->password)) {
                Auth::login($admin_user);
                return redirect()->intended(url('/admin'));
            }

            return back()->withError('Invalid email or password.');            
        }
        catch (\Exception $e)
        {
            return back()->withError('Error has occurred, please try again later.');
        }
    }

    public function adminLogout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->intended(url('/admin/login'));
    }

}
