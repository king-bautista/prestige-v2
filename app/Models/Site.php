<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'serial_number',
        'name',
        'descriptions',
        'site_logo',
        'site_banner',
        'site_background',
        'site_background_portrait',
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
    protected $table = 'sites';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function saveMeta($meta_data)
    {
        foreach ($meta_data as $key => $data) {
            SiteMeta::updateOrCreate(
                [
                   'site_id' => $this->id,
                   'meta_key' => $key
                ],
                [
                   'meta_value' => $data,
                ],
            );
        }
    }
}
