<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\UsersControllerInterface;
use Illuminate\Http\Request;

use App\Helpers\PasswordHelper;
use App\Http\Requests\RegistrationRequest;
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
        $this->module_id = 45; 
        $this->module_name = 'Portal User';
    }

    public function index()
    {
        return view('portal.users');
    }

    public function list(Request $request)
    {   
        try
        {
            $this->permissions = UserViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $user = UserViewModel::when(request('search'), function($query){
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

            $portal_user = User::create($data);

            $meta_details = ["first_name" => $request->first_name, "last_name" => $request->last_name];
            $portal_user->saveMeta($meta_details);
            $portal_user->saveRoles($request->roles);

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

    public function update(Request $request)
    {
        try
    	{
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
            $user->saveRoles($request->roles);

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
