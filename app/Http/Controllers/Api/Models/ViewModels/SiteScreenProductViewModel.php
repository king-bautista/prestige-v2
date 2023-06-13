<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteScreenProductViewModel extends Model
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
            return $site_screen->site_name.' - '.$site_screen->name.', '.$site_screen->building_name.', '.$site_screen->floor_name. ' ('.$this->ad_type.' / '.$this->dimension.')';
        }
        return null;
    }
}
