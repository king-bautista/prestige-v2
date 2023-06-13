<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Site;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;
use App\Models\SiteScreenUptimeTemp;
use Carbon\Carbon;

class SiteScreenUptimeViewModel extends Model
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
        'screen_status',
        'site_name',
        'building_name',
        'floor_name',
        'screen_location',
    ];

    public function getScreenStatusAttribute()
    {
        $date_now = date('Y-m-d');
        $time_now = date('H:i:s');

        $uptime = SiteScreenUptimeTemp::where('site_screen_id', $this->id)->where('up_time_date', $date_now)->latest()->first();
        if($uptime) {
            $uptime_start = Carbon::parse($date_now.' '.$uptime->up_time_hours);
            $uptime_end = Carbon::parse($date_now.' '.$time_now);    

            $time_diff = $uptime_start->diffInHours($uptime_end);
            if($time_diff <= 1)
                return 'online';
            return 'offline';
        }        
        return 'offline';
    }

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

    public function getScreenLocationAttribute() 
    {
        return $this->name.', '.$this->building_name.', '.$this->floor_name.' ('.$this->site_name.')';
    }
}
