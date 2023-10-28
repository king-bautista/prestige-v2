<?php

namespace App\Models\AdminViewModels;

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

    public function getModules()
    {
        $role_id = $this->id;
        $role_type = $this->type;

        return Module::select('modules.*', 'permissions.role_id', 'permissions.module_id', 'permissions.can_view', 'permissions.can_add', 'permissions.can_edit', 'permissions.can_delete')
        ->join('permissions', function($join) use ($role_id, $role_type) {
            $join->on('modules.id', '=', 'permissions.module_id');
            $join->where('permissions.role_id','=', $role_id)->where('modules.role', $role_type);
        });
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getPermissionsAttribute() 
    {
        $n_modules = [];
        $role_id = $this->id;

        $modules = $this->getModules()->whereNull('modules.parent_id')->get();
        foreach($modules as $index => $module) {
            $n_modules[] = [
                'id' => $module->id,
                'name' => $module->name,
                'parent_id' => $module->parent_id,
                'module_id' => $module->module_id,
                'link' => $module->link,
                'class_name' => $module->class_name,
                'can_view' => $module->can_view,
                'can_edit' => $module->can_edit,
                'can_delete' => $module->can_delete,
                'can_add' => $module->can_add,
                'child_modules' => $this->getModules()->where('modules.parent_id', $module->id)->get()
            ];
        }

        if($n_modules)
            return $n_modules;
        return null;
    }
    
}
