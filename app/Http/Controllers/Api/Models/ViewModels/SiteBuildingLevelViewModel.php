<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\SiteMap;
use App\Models\SiteBuilding;

class SiteBuildingLevelViewModel extends Model
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
    protected $table = 'site_building_levels';

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
        // 'map_details',
        // 'map_file',
        // 'map_preview_path',
        // 'is_default',
        'building_name',
        'building_floor_name',
    ];

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    // public function getMapDetailsAttribute() 
    // {
    //     return SiteMap::where('site_building_level_id', $this->id)->first();
    // }

    // public function getMapPreviewPathAttribute()
    // {
    //     $site_map = SiteMap::where('site_building_level_id', $this->id)->first();
    //     if($site_map->map_preview)
    //         return asset($site_map->map_preview);
    //     return asset('/images/no-image-available.png');
    // }

    // public function getIsDefaultAttribute() 
    // {
    //     return SiteMap::where('site_building_level_id', $this->id)->first()->is_default;
    // }

    public function getBuildingNameAttribute() 
    {
        return SiteBuilding::find($this->site_building_id)->name;
    }

    public function getBuildingFloorNameAttribute() 
    {
        return SiteBuilding::find($this->site_building_id)->name. ' - '.$this->name;
    }

    // public function getMapFileAttribute() 
    // {
    //     return SiteMap::where('site_building_level_id', $this->id)->first()->map_file;
    // }    
}
