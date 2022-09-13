<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteBuildingLevel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_id',
        'site_building_id',
        'name',
        'active',
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
    protected $table = 'site_building_levels';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function saveMap($request)
    {
        $site_id = session()->get('site_id');

        $map_file = $request->file('map_file');
        $map_file_path = '';
        if($map_file) {
            $originalname = $map_file->getClientOriginalName();
            $map_file_path = $map_file->move('uploads/map/files/', str_replace(' ','-', $originalname)); 
        }

        $map_preview = $request->file('map_preview');
        $map_preview_path = '';
        if($map_preview) {
            $originalname = $map_preview->getClientOriginalName();
            $map_preview_path = $map_preview->move('uploads/map/preview/', str_replace(' ','-', $originalname)); 
        }

        $site_map = SiteMap::where('site_id', $site_id)
                           ->where('site_building_id', $request->site_building_id)
                           ->where('site_building_level_id', $this->id)->first();

        SiteMap::updateOrCreate(
            [
               'site_id' => $site_id,
               'site_building_id' => $request->site_building_id,
               'site_building_level_id' => $this->id
            ],
            [
               'descriptions' => $request->name,
               'position_x' => $request->position_x,
               'position_y' => $request->position_y,
               'position_z' => $request->position_z,
               'text_y_position' => $request->text_y_position,
               'default_zoom' => $request->default_zoom,
               'default_zoom_desktop' => $request->default_zoom_desktop,
               'default_zoom_mobile' => $request->default_zoom_mobile,
               'name' => ($map_file_path) ? str_replace('\\', '/', $map_file_path) : $site_map->name,
               'map_preview' => ($map_preview_path) ? str_replace('\\', '/', $map_preview_path) : $site_map->map_preview,
               'active' => ($request->active == 'false') ? 0 : 1,
               'is_default' => ($request->is_default == 'false') ? 0 : 1,
            ]
        );
    }
}
