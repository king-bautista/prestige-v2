<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Classification;

class CompanyViewModel extends Model
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
    protected $table = 'companies';

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
        'classification_name',
    ]; 

    public function getClassificationNameAttribute() 
    {

        $classification = Classification::find($this->classification_id);
        if($classification)
            return $classification['name'];
        return null;
    }
}
