<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function getPermissions()
    {   
        return $this->hasMany('App\Models\ViewModels\PermissionViewModel', 'role_id', 'id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getPermissionsAttribute() 
    {
        return $this->getPermissions()->get();
    }
}
