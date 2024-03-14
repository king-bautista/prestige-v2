<?php

namespace App\Models\AdminViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Site;
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
        'site_name',
        'building_name',
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
        $site_building = SiteBuilding::find($this->site_building_id);
        if($site_building)
            return $site_building->name;
        return null;
    }

    public function getBuildingFloorNameAttribute() 
    {
        if($this->building_name) 
            return $this->name. ' - '.$this->building_name;
        return null;
    } 
}
