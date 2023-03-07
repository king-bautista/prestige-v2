<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ClientUserControllerInterface;
use Illuminate\Http\Request;
use App\Http\Requests\ClientUserRequest;
use App\Http\Requests\EditClientUserRequest;

use App\Helpers\PasswordHelper;
use App\Models\ViewModels\UserViewModel;
use App\Models\ViewModels\AdminViewModel;
use App\Models\User;

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

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $user = UserViewModel::when(request('search'), function($query){
                return $query->where('full_name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('email', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($user, 'Successfully Retreived!', 200);
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

    public function store(ClientUserRequest $request)
    {
        try
    	{
            $salt = PasswordHelper::generateSalt();
            $password = PasswordHelper::generatePassword($salt, $request->password);
            $data = [
                'company_id' => $request->company['id'],
                'full_name' => $request->last_name.', '.$request->first_name,
                'email' => $request->email,
                'salt' => $salt,
                'password' => $password,
                'active' => 1
            ];

            $user = User::create($data);

            $meta_details = ["first_name" => $request->first_name, "last_name" => $request->last_name];
            $user->saveMeta($meta_details);
            $user->saveRoles($request->roles);
            $user->saveBrands($request->brands);
            $user->saveSites($request->sites);
            $user->saveScreens($request->screens);

            return $this->response($user, 'Successfully Created!', 200);
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

    public function update(EditClientUserRequest $request)
    {
        try
    	{
            $user = User::find($request->id);
            $password = PasswordHelper::generatePassword($user->salt, $request->password);
            $data = [
                'company_id' => $request->company['id'],
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
            $user->saveBrands($request->brands);
            $user->saveSites($request->sites);
            $user->saveScreens($request->screens);

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


}
