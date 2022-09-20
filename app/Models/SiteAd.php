<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteAd extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'ad_type',
        'file_path',
        'file_type',
        'display_order',
        'display_duration',
        'start_date',
        'end_date',
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
    protected $table = 'site_ads';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function saveTenants($tenants)
    {

        if($tenants) {

            TenantAd::where('site_ad_id', $this->id)->delete();

            $tenant_ids =  explode(',',$tenants);
            foreach ($tenant_ids as $index => $data) {
                TenantAd::updateOrCreate(
                    [
                       'site_ad_id' => $this->id,
                       'site_tenant_id' => $data,
                    ],
                );
            }
        }
    }

}
