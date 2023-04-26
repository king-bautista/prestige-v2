<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ConcernViewModel extends Model
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
    protected $table = 'concerns';

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
        'shorten_name',
        'shorten_description',
    ]; 

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
       
    public function getShortenNameAttribute()
    {
       
        return Str::limit($this->name, 50, '...'); 
    }

    public function getShortendescriptionAttribute()
    {
       
        return Str::limit($this->description, 150, '...');
    }

}