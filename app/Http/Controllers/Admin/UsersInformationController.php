<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\UsersInformationControllerInterface;
use Illuminate\Http\Request;    
use App\Http\Requests\UserInformationRequest;

use App\Helpers\PasswordHelper;
use App\Models\Admin;
use App\Models\AdminViewModels\AdminViewModel;
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

    public function details()
    {
        try {
            $users_information = AdminViewModel::find(Auth::user()->id);
            return $this->response($users_information, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    
    public function updateProfile(UserInformationRequest $request)
    {
        try
    	{
            $profile_image = $request->file('profile_image');
            $file_path_path = null;

            if($profile_image) {
                $originalname = $profile_image->getClientOriginalName();
                $file_path_path = $profile_image->move('uploads/media/profile/', str_replace(' ','-', $originalname));
            }

            $user = Admin::find($request->id);
            $password = PasswordHelper::generatePassword($user->salt, $request->password);
            $data = [
                'full_name' => $request->last_name.', '.$request->first_name,
                'email' => $request->email
            ];

            if($request->password)
                $data['password'] = $password;

            $user->update($data);

            $meta_details = [
                "first_name" => $request->first_name, 
                "last_name" => $request->last_name,
                "mobile" => $request->mobile,
                "address" => $request->address,
            ];

            if($file_path_path)
                $meta_details["profile_image"] = $file_path_path;

            $user->saveMeta($meta_details);
            return $this->response($user, 'Successfully Modified!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}
