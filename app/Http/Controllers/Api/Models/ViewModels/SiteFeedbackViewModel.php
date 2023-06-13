<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Site;

class SiteFeedbackViewModel extends Model
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
    protected $table = 'site_feedback';

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
        'site_name',
    ];

    public function getSiteNameAttribute() 
    {
        return Site::find($this->site_id)->name;
    }
}
