<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ClientUserControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\ClientUserRequest;
use App\Http\Requests\EditClientUserRequest;
use App\Helpers\PasswordHelper;
use App\Exports\Export;
use Storage;
use Hash;

use App\Models\User;
use App\Models\AdminViewModels\UserViewModel;

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
        try {
            $user = UserViewModel::when(request('search'), function ($query) {
                return $query->where('full_name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('email', 'LIKE', '%' . request('search') . '%');
            })
            ->when(request('order'), function ($query) {
                $column = $this->checkcolumn(request('order'));
                return $query->orderBy($column, request('sort'));
            })
                ->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($user, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(ClientUserRequest $request)
    {
        try {
            $salt = PasswordHelper::generateSalt();
            $password = PasswordHelper::generatePassword($salt, $request->password);
            $data = [
                'company_id' => $request->company['id'],
                'full_name' => $request->last_name . ', ' . $request->first_name,
                'email' => $request->email,
                'salt' => $salt,
                'password' => $password,
                'active' => 1
            ];

            $user = User::create($data);

            $meta_details = ["first_name" => $request->first_name, "last_name" => $request->last_name];
            $user->saveMeta($meta_details);
            $user->saveRoles($request->roles);

            return $this->response($user, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function details($id)
    {
        try {
            $user = UserViewModel::find($id);
            return $this->response($user, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(EditClientUserRequest $request)
    {
        try {
            $user = User::find($request->id);
            $password = PasswordHelper::generatePassword($user->salt, $request->password);
            $data = [
                'company_id' => $request->company['id'],
                'full_name' => $request->last_name . ', ' . $request->first_name,
                'email' => $request->email,
                'active' => $request->isActive
            ];

            if ($request->password)
                $data['password'] = $password;

            $user->update($data);

            $meta_details = ["first_name" => $request->first_name, "last_name" => $request->last_name];
            $user->saveMeta($meta_details);
            $user->saveRoles($request->roles);

            return $this->response($user, 'Successfully Modified!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            return $this->response($user, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsv()
    {
        try {

            $client_user_management = UserViewModel::get();
            $reports = [];
            foreach ($client_user_management as $user) {
                $full_name = explode(",", $user->full_name);
                $reports[] = [
                    'id' => $user->id,
                    'first_name' => $full_name[0],
                    'last_name' => $full_name[1],
                    'email' => $user->email,
                    'company_id' => $user->company['id'],
                    'company_name' => $user->company['name'],
                    'role_id' => $this->roles($user->roles, 'id'),
                    'role_name' => $this->roles($user->roles, 'name'),
                    'login_attempt' => $user->login_attempt,
                    'is_blocked' => $user->is_blocked,
                    'active' => $user->active,
                    'created_by' => $user->created_by,
                    'updated_by' => $user->updated_by,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'deleted_at' => $user->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "client_user.csv";
            // Store on default disk
            Excel::store(new Export($reports), $directory . $filename);

            $data = [
                'filepath' => '/storage/export/reports/' . $filename,
                'filename' => $filename
            ];

            if (Storage::exists($directory . $filename))
                return $this->response($data, 'Successfully Retreived!', 200);

            return $this->response(false, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvTemplate()
    {
        try {
            $reports[] = [
                'id' => '',
                    'first_name' => '',
                    'last_name' => '',
                    'email' => '',
                    'company_id' => '',
                    'company_name' => '',
                    'role_id' => '',
                    'role_name' => '',
                    'login_attempt' => '',
                    'is_blocked' => '',
                    'active' => '',
                    'created_by' => '',
                    'updated_by' => '',
                    'created_at' => '',
                    'updated_at' => '',
                    'deleted_at' => '',
            ];

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "client-user-template.csv";
            // Store on default disk
            Excel::store(new Export($reports), $directory . $filename);

            $data = [
                'filepath' => '/storage/export/reports/' . $filename,
                'filename' => $filename
            ];

            if (Storage::exists($directory . $filename))
                return $this->response($data, 'Successfully Retreived!', 200);

            return $this->response(false, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function brands($brands, $field)
    {
        $user_brand = [];
        if (count($brands) > 0) {
            foreach ($brands as $brand) {
                $user_brand[] = ($field == 'id') ? (string) $brand->id." " : $brand->name;
            }
            return implode(",", $user_brand);
        }
        return 0;
    }
    public function contracts($contracts, $field)
    {
        $user_contract = [];
        if (count($contracts) > 0) {
            foreach ($contracts as $contract) {
                $user_contract[] = ($field == 'id') ? (string) $contract->id." " : $contract->name;
            }
            return implode(",", $user_contract);
        }
        return 0;
    }

    public function roles($roles, $field)
    {
        $user_role = [];
        foreach ($roles as $role) {
            $user_role[] = ($field == 'id') ? $role->id : $role->name;
        }
        return implode(",",$user_role);
    }
}
