<?php

namespace App\Models\ViewModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Role;
use App\Models\Permission;

class AdminViewModel extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'salt',
        'remember_token',
        'login_attempt',
        'is_blocked',
        'is_active',
        'activation_token',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'admins';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Append additiona info to the return data
     *
     * @var string
     */
	public $appends = [
        'details',
        'roles',
        'permissions',
    ];

    public function getUserDetails()
    {   
        return $this->hasMany('App\Models\AdminMeta', 'admin_id', 'id');
    }

    public function getRoles()
    {
        return $this->hasMany('App\Models\AdminRoles', 'admin_id', 'id');
    }

    public function getPermissions()
    {
        $role_ids = $this->getRoles()->pluck('role_id')->toArray();
        return Permission::whereIn('role_id', $role_ids)->where('active', 1)->whereIn('modules.role',['Admin','Portal'])->whereNull('modules.deleted_at')
                        ->selectRaw('modules.id, modules.parent_id, modules.name, modules.link, modules.class_name, max(permissions.can_view) AS can_view, max(permissions.can_add) AS can_add, max(permissions.can_edit) AS can_edit, max(permissions.can_delete) AS can_delete')
                        ->leftJoin('modules', 'permissions.module_id', '=', 'modules.id')
                        ->groupBy('permissions.module_id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getDetailsAttribute() 
    {
        return $this->getUserDetails()->pluck('meta_value','meta_key')->toArray();
    }

    public function getRolesAttribute() 
    {
        $role_ids = $this->getRoles()->pluck('role_id')->toArray();
        return Role::whereIn('id', $role_ids)->get();
    }

    public function getPermissionsAttribute() 
    {
        $permissions_group = [];
        $permissions_parent = $this->getPermissions()->whereNull('modules.parent_id')->get();
      
        foreach($permissions_parent as $index => $permission) {
            $permissions_group[$permission->id] = $permission;
            $permissions_group[$permission->id]['sub_permissions'] = $this->getPermissions()->where('modules.parent_id', $permission->id)->get();
        }

        return $permissions_group;
    }


}
