<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteScreenProductPlaylistViewModel extends Model
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
    protected $table = 'site_screen_products';

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
        'site_screen_details',
        'site_screen_location',
        'site_name',
        'playlist',
    ];

    public function getSiteScreenDetailsAttribute() 
    {
        $site_screen = SiteScreenViewModel::find($this->site_screen_id);
        if($site_screen) {
            return $site_screen;
        }
        return null;
    }

    public function getSiteScreenLocationAttribute() 
    {
        $site_screen = SiteScreenViewModel::find($this->site_screen_id);
        if($site_screen) {
            return $site_screen->site_code_name.' - '.$site_screen->name.', '.$site_screen->building_name.', '.$site_screen->floor_name. ' ('.$this->ad_type.' / '.$this->dimension.')';
        }
        return null;
    }

    public function getSiteNameAttribute() 
    {
        if($this->site_screen_details) {
            return $this->site_screen_details->site_name;
        }
        return null;
    }

    public function getPlaylistAttribute() 
    {
        $play_list = PlayListViewModel::where('play_lists.site_screen_id', $this->site_screen_id)
        ->where('advertisement_screens.ad_type', $this->ad_type)
        ->whereNull('content_management.deleted_at')
        ->where('content_management.active', 1)
        ->join('content_management', 'play_lists.content_id', '=', 'content_management.id')
        ->join('advertisement_screens', function($join)
        {
            $join->on('content_management.material_id', '=', 'advertisement_screens.material_id')
                 ->on('advertisement_screens.site_screen_id','=', 'play_lists.site_screen_id');
        })
        ->select('play_lists.*', 'advertisement_screens.ad_type', 'content_management.updated_at')
        ->orderBy('play_lists.sequence', 'ASC')
        ->get();

        if($play_list)
            return $play_list;
        return null;
    }

}
