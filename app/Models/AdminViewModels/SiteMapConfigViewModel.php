<?php

namespace App\Models\AdminViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteMapConfigViewModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'is_default',
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
    protected $table = 'site_map_configs';

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
        'map_details',
        'site_screen_name',
        'site_screen_location',
        'map_preview_path',
    ];

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getMapDetailsAttribute() 
    {
        return SiteMapViewModel::find($this->site_map_id);
    }

    public function getSiteScreenNameAttribute() 
    {
        if($this->site_screen_id)
            return SiteScreenViewModel::find($this->site_screen_id)->name;
        return null;
    }

    public function getSiteScreenLocationAttribute() 
    {
        if($this->map_details)
            return $this->map_details->building_name . ', '.$this->map_details->building_floor_name;
        return null;
    }

    public function getMapPreviewPathAttribute()
    {
        if($this->map_details)
            return asset($this->map_details->map_preview_path);
        return asset('/images/no-image-available.png');
    }
}
