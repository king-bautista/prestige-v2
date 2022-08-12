<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Module;

class RoleViewModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s A',
        'updated_at' => 'datetime:Y-m-d H:i:s A',
        'deleted_at' => 'datetime:Y-m-d H:i:s A',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'roles';

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
        'permissions',
    ];

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getPermissionsAttribute() 
    {
        $role_id = $this->id;
        $modules = Module::select('modules.*', 'permissions.role_id', 'permissions.module_id', 'permissions.can_view', 'permissions.can_add', 'permissions.can_edit', 'permissions.can_delete')
        ->leftJoin('permissions', function($join) use ($role_id) {
            $join->on('modules.id', '=', 'permissions.module_id');
            $join->where('permissions.role_id','=', $role_id);
        })->get();

        if($modules)
            return $modules;
        return null;
    }
}
