<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionViewModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'permissions';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $module = '';

    /**
     * Append additiona info to the return data
     *
     * @var string
     */
	public $appends = [
        'name',
        'class_name',
        'parent_id',
    ];

    public function getModules()
    {   
        return $this->hasOne('App\Models\Module', 'id', 'module_id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getNameAttribute() 
    {
        $this->module = $this->getModules()->first();
        if($this->module)
            return $this->module->name;
        return null;
    }
    
    public function getClassNameAttribute() 
    {
        if($this->module)
            return $this->module->class_name;
        return null;
    }

    public function getParentIdAttribute() 
    {
        if($this->module)
            return $this->module->parent_id;
        return null;
    }

}
