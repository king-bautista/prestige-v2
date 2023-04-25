<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Module;
use App\Models\Company;
use App\Models\UserActivityLog;

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
        'company_name',
        'old_bindings'
    ];

    /****************************************
     *           ATTRIBUTES PARTS            *
     ****************************************/

    public function getOldBindingsAttribute()
    {
        $result = UserActivityLog::where('transaction_id', $this->transaction_id)
            ->where('module_accessed', 'like', substr($this->module_accessed, 0, strrpos($this->module_accessed, "/")) . '%')
            ->where('created_at', '<', $this->created_at)
            ->orderBy('created_at', 'desc')
            ->take(1)
            ->get()
            ->toArray();

        return (count($result) > 0) ? $result[0]['bindings'] : '';
    }

    public function getCompanyNameAttribute()
    {
        return Company::find($this->company_id)->name;
    }
}
