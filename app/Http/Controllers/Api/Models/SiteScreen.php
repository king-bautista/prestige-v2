<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteScreen extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'serial_number',
        'site_id',
        'site_building_id',
        'site_building_level_id',
        'site_point_id',
        'screen_type',
        'orientation',
        'product_application',
        'physical_size_diagonal',
        'physical_size_width',
        'physical_size_height',
        'physical_serial_number',
        'dimension',
        'width',
        'height',
        'kiosk_id',
        'name',
        'slots',
        'active',
        'is_default',
        'is_exclusive',
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
    protected $table = 'site_screens';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
