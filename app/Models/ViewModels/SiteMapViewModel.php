<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Site;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;

class SiteMapViewModel extends Model
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
    protected $table = 'site_maps';

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
        'building_name',
        'floor_name',
        'map_file_path',
        'map_preview_path',        
        'building_floor_name',
    ];

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getSiteNameAttribute() 
    {
        return Site::find($this->site_id)->name;
    }

    public function getBuildingNameAttribute() 
    {
        return SiteBuilding::find($this->site_building_id)->name;
    }

    public function getFloorNameAttribute() 
    {
        return SiteBuildingLevel::find($this->site_building_level_id)->name;
    }
    
    public function getMapFilePathAttribute() 
    {
        if($this->map_file)
            return asset($this->map_file);
        return asset('/images/no-image-available.png');
    } 

    public function getMapPreviewPathAttribute()
    {
        if($this->map_preview)
            return asset($this->map_preview);
        return asset('/images/no-image-available.png');
    }

    public function getBuildingFloorNameAttribute() 
    {
        return $this->building_name.' - '.$this->floor_name;
    }
}
