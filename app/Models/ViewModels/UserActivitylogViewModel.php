<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Module;
use App\Models\Company;

class UserActivitylogViewModel extends Model
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
    protected $table = 'user_activity_logs';

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
        //'module_name',
        'company_name',
    ]; 

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
       
    // public function getModuleNameAttribute()
    // {
       
    //     return Module::find($this->module_id)->name;
    // }

    public function getCompanyNameAttribute()
    {
       
        return Company::find($this->company_id)->name;
    }

}