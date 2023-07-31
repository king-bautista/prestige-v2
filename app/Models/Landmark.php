<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Landmark extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_id',
        'landmark',
        'descriptions',
        'name',
        'title',
        'image_url',
        'image_thumbnail_url',
        'active',
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
    protected $table = 'landmarks';

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
        'image_url_path',
        'image_thumbnail_url_path',
    ];

    public function getSiteNameAttribute() 
    {
        return Site::find($this->site_id)->name;
    }

    public function getImageUrlPathAttribute()
    {
        if($this->image_url)
            return asset($this->image_url);
        return asset('/images/no-image-available.png');
    } 

    public function getImageThumbnailUrlPathAttribute()
    {
        if($this->image_thumbnail_url)
            return asset($this->image_thumbnail_url);
        return asset('/images/no-image-available.png');
    } 

}
