<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Admin\Interfaces\UsersControllerInterface;
use Illuminate\Http\Request;

use App\Helpers\PasswordHelper;
use App\Http\Requests\RegistrationRequest;
use App\Models\Admin;
use App\Models\ViewModels\AdminViewModel;
use Hash;

class UsersController extends AppBaseController implements UsersControllerInterface
{
    /************************************
    * 			USERS MANAGEMENT		*
    ************************************/
    public function index()
    {
        return view('admin.users');
    }

    public function list(Request $request)
    {
        try
        {
            $user = AdminViewModel::when(request('search'), function($query){
                return $query->where('full_name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('email', 'LIKE', '%' . request('search') . '%');
            })
            ->where('full_name', '<>', 'Administrator')
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($user, 'Successfully Retreived!', 200);
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

    public function details($id)
    {
        try
        {
            $user = AdminViewModel::find($id);
            return $this->response($user, 'Successfully Retreived!', 200);
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

    public function store(RegistrationRequest $request)
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
            $admin_user->saveRoles($request->roles);

            return $this->response($admin_user, 'Successfully Created!', 200);
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

    public function update(Request $request)
    {
        try
    	{
            $user = Admin::find($request->id);
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
            $user->saveRoles($request->roles);

            return $this->response($user, 'Successfully Created!', 200);
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

    public function delete($id)
    {
        try
    	{
            $user = Admin::find($id);
            $user->delete();
            return $this->response($user, 'Successfully Deleted!', 200);
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
