<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteTenant extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'brand_id',
        'site_id',
        'site_building_id',
        'site_building_level_id',
        'company_id',
        'view_count',
        'like_count',
        'active',
        'is_subscriber',
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
    protected $table = 'site_tenants';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function saveMeta($meta_data)
    {
        foreach ($meta_data as $key => $data) {
            SiteTenantMeta::updateOrCreate(
                [
                   'site_tenant_id' => $this->id,
                   'meta_key' => $key
                ],
                [
                   'meta_value' => $data,
                ],
            );
        }
    }
}
