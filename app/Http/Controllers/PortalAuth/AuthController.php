<?php

namespace App\Http\Controllers\PortalAuth;

use App\Http\Controllers\AppBaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\PortalAuth\Interfaces\AuthControllerInterface;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

use App\Models\User;
use Session;
use Hash;

class AuthController extends AppBaseController implements AuthControllerInterface
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
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirect_to = '/portal';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:portal')->except('logout');
    }

    public function login()
    {
        return view('portal.login');
    }

    public function portalLogin(LoginRequest $request)
    {   
        try
        {   
            $portal_user = User::where('email', '=', $request->email)->where('active', true)->first();
           
            if ($portal_user && Hash::check($portal_user->salt.env("PEPPER_HASH").$request->password, $portal_user->password)) {
                Auth::guard('portal')->login($portal_user);
                return redirect()->intended(url('/portal'));
            }

            return back()->withError('Invalid email or password.');            
        }
        catch (\Exception $e)
        {
            return back()->withError('Error has occurred, please try again later.');
        }
    }

    public function portalLogout(Request $request)
    {
        if(Auth::guard('portal')->check()) // this means that the portal was logged in.
        {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('portal.login');
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->intended(url('/portal/login'));
    }

}
