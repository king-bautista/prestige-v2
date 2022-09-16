<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryLabel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'site_id',
        'name',
    ];

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
    protected $table = 'category_labels';

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
        'category_name',
        'site_name',
    ]; 

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getCategoryNameAttribute()
    {
        $name = Category::find($this->category_id)->name;
        if($name)
            return $name;
        return null;
    }

    public function getSiteNameAttribute()
    {
        $name = Site::find($this->site_id)->name;
        if($name)
            return $name;
        return null;
    }

}
