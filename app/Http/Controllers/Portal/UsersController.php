<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\UsersControllerInterface;
use Illuminate\Http\Request;

use App\Helpers\PasswordHelper;
use App\Http\Requests\PortalUserRequest;
use App\Models\User;
use App\Models\ViewModels\UserViewModel;
use Hash;

class UsersController extends AppBaseController implements UsersControllerInterface
{
    /************************************
    * 			USERS MANAGEMENT		*
    ************************************/
    public function __construct()
    {
        $this->module_id = 48; 
        $this->module_name = 'Manage Account';
    }

    public function index()
    {
        return view('portal.users');
    }

    public function profile()
    {
        return view('portal.profile');
    }

    public function brands()
    {
        return view('portal.brands');
    }

    public function sites()
    {
        return view('portal.user_sites');
    }

    public function list(Request $request)
    {   
        try
        { 
            // GET CURRENT LOGIN USER
            $user = UserViewModel::find(Auth::guard('portal')->user()->id);

            $user_list = UserViewModel::when(request('search'), function($query){
                return $query->where('full_name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('email', 'LIKE', '%' . request('search') . '%');
            })
            ->where('full_name', '<>', 'Administrator')
            ->where('company_id', $user->company_id)
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($user_list, 'Successfully Retreived!', 200);
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

    public function details($id)
    {
        try
        {
            if(!$id)
                $id = Auth::guard('portal')->user()->id;

            $user = UserViewModel::find($id);
            return $this->response($user, 'Successfully Retreived!', 200);
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

    public function store(PortalUserRequest $request)
    {
        try
    	{
            $user = UserViewModel::find(Auth::guard('portal')->user()->id);

            $salt = PasswordHelper::generateSalt();
            $password = PasswordHelper::generatePassword($salt, $request->password);
            $data = [
                'company_id' => $user->company_id,
                'full_name' => $request->last_name.', '.$request->first_name,
                'email' => $request->email,
                'salt' => $salt,
                'password' => $password,
                'active' => 1
            ];

            $portal_user = User::create($data);

            $meta_details = ["first_name" => $request->first_name, "last_name" => $request->last_name];
            $portal_user->saveMeta($meta_details);
            $portal_user->saveRoles($user->roles);

            return $this->response($portal_user, 'Successfully Created!', 200);
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

    public function update(PortalUserRequest $request)
    {
        try
    	{
            $current_user = UserViewModel::find(Auth::guard('portal')->user()->id);

            $user = User::find($request->id);
            $password = PasswordHelper::generatePassword($user->salt, $request->password);
            $data = [
                'full_name' => $request->last_name.', '.$request->first_name,
                'email' => $request->email,
                'active' => $request->isActive
            ];

            if($request->password)
                $data['password'] = $password;

            $user->update($data);

            $meta_details = ["first_name" => $request->first_name, "last_name" => $request->last_name];
            $user->saveMeta($meta_details);
            $user->saveRoles($current_user->roles);

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

    public function updateProfile(PortalUserRequest $request)
    {
        try
    	{
            $profile_image = $request->file('profile_image');
            $file_path_path = null;

            if($profile_image) {
                $originalname = $profile_image->getClientOriginalName();
                $file_path_path = $profile_image->move('uploads/media/profile/', str_replace(' ','-', $originalname));
            }

            $user = User::find($request->id);
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

    public function delete($id)
    {
        try
    	{
            $user = User::find($id);
            $user->delete();
            return $this->response($user, 'Successfully Deleted!', 200);
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

    public function userBrands()
    {
        try
        {
            $user = UserViewModel::find(Auth::guard('portal')->user()->id);
            return $this->response($user->brands, 'Successfully Created!', 200);
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

    public function userSites()
    {
        try
        {
            $user = UserViewModel::find(Auth::guard('portal')->user()->id);
            return $this->response($user->sites, 'Successfully Created!', 200);
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
