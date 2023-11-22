<?php

namespace App\Models\AdminViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Site;
use App\Models\SiteMeta;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;

class SiteScreenPlaylistViewModel extends Model
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
        'site_code_name',
        'building_name',
        'floor_name',
        'screen_location',
        'site_screen_location',
        'dimensions',
        'playlist',
    ];

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getSiteNameAttribute() 
    {
        $site = Site::find($this->site_id);
        if($site)
            return $site->name;
        return null;
    }

    public function getSiteCodeNameAttribute() 
    {
        $site_meta = SiteMeta::where('site_id', $this->site_id)->where('meta_key', 'site_code')->first();
        if($site_meta)
            return $site_meta->meta_value;
        return null;
    }

    public function getBuildingNameAttribute() 
    {
        $site_building = SiteBuilding::find($this->site_building_id);
        if($site_building)
            return $site_building->name;
        return null;
    }

    public function getFloorNameAttribute() 
    {
        $site_level = SiteBuildingLevel::find($this->site_building_level_id);
        if($site_level)
            return $site_level->name;
        return null;
    }

    public function getScreenLocationAttribute() 
    {
        return $this->name.', '.$this->building_name.', '.$this->floor_name;
    }

    public function getSiteScreenLocationAttribute() 
    {
        return $this->site_code_name.' - '.$this->name.', '.$this->building_name.', '.$this->floor_name. ' ('.$this->product_application.' / '.$this->orientation.')';
    }

    public function getDimensionsAttribute() 
    {
        return PlayListViewModel::where('site_screen_id', $this->id)->groupBy('dimension')->get();
    }

    public function getPlaylistAttribute() 
    {
        return PlayListViewModel::where('site_screen_id', $this->id)->orderBy('sequence')->get();
    }

}
