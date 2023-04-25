<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Site;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;
use App\Models\ExclusiveScreen;

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
	public $appends = [
        'site_name',
        'building_name',
        'floor_name',
        'screen_type_name',
        'screen_location',
        'site_screen_location',
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

    public function getScreenTypeNameAttribute() 
    {
        $site_name = Site::find($this->site_id)->name;
        return $site_name.' - '.$this->name . ' ( '.$this->screen_type.' )';
    }

    public function getScreenLocationAttribute() 
    {
        return $this->name.', '.$this->building_name.', '.$this->floor_name;
    }

    public function getSiteScreenLocationAttribute() 
    {
        return $this->site_name.' - '.$this->name.', '.$this->building_name.', '.$this->floor_name. ' ('.$this->product_application.' / '.$this->orientation.')';
    }

}
