<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'type',
        'company_id',
        'active',
    ];

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

    public function setPermissions($permissions)
    {
        foreach ($permissions as $key => $permission) {
            Permission::updateOrCreate(
                [
                   'role_id' => $this->id,
                   'module_id' => $permission['id']
                ],
                [
                   'can_view' => (isset($permission['can_view'])) ? $permission['can_view'] : 0,
                   'can_add' => (isset($permission['can_add'])) ? $permission['can_add'] : 0,
                   'can_edit' => (isset($permission['can_edit'])) ? $permission['can_edit'] : 0,
                   'can_delete' => (isset($permission['can_delete'])) ? $permission['can_delete'] : 0,
                ],
            );
        }
    }

}
