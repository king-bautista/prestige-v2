<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteMapConfig extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_map_id',
        'site_building_id',
        'site_building_level_id',
        'site_screen_id',
        'map_type',
        'start_scale',
        'start_x',
        'start_y',
        'default_zoom',
        'default_x',
        'default_y',
        'name_angle',
        'view_angle',
        'building_label_height',
        'building_label_space',
        'building_animation_height',
        'floor_label_height',
        'floor_label_space',
        'floor_animation_height',
        'active',
        'is_default',
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
    protected $table = 'site_map_configs';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
