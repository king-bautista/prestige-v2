<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\Company;

class WorkflowViewModel extends Model
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
    protected $table = 'workflows';

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
        'user_name',
        'company_name',
    ]; 

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/   
    public function getUserNameAttribute()
    {
        $user = User::find($this->user_id);
        if($user)
            return $user['full_name'];
        return null;
    }
    public function getCompanyNameAttribute()
    {
        $company = Company::find($this->company_id);
        if($company)
            return $company['name'];
        return null;
    }
}
