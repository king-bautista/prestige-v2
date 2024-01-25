<?php

namespace App\Models\AdminViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Module;

class ModuleViewModel extends Model
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
    protected $table = 'modules';

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
        'parent_link',
        'child_modules',
    ];

    public function getChildModules()
    {   
        return $this->hasMany('App\Models\Module', 'parent_id', 'id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getParentLinkAttribute() 
    {

        $parent_link = Module::find($this->parent_id);
        if($parent_link)
            return $parent_link['name'];
        return null;
    }

    public function getChildModulesAttribute() 
    {
        $child_modules = $this->getChildModules()->get();
        if($child_modules)
            return $child_modules;
        return null;
    }
    
}
