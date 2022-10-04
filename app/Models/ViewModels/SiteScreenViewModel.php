<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;

class SiteScreenViewModel extends Model
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
    protected $table = 'site_screens';

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
	// public $appends = [
    //     'building_name',
    //     'floor_name',
    // ];

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    // public function getBuildingNameAttribute() 
    // {
    //     return SiteBuilding::find($this->site_building_id)->name;
    // }

    // public function getFloorNameAttribute() 
    // {
    //     return SiteBuildingLevel::find($this->site_building_level_id)->name;
    // }

}
