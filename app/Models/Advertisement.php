<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'contract_id',
        'brand_id',
        'status_id',
        'display_duration',
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
    protected $table = 'advertisements';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function saveScreens($screens)
    {
        if(count($screens) > 0) {
            AdvertisementScreen::where('advertisement_id', $this->id)->delete();

            foreach ($screens as $data) {
                AdvertisementScreen::updateOrCreate(
                    [
                       'advertisement_id' => $this->id,
                       'site_screen_id' => $data->id,
                       'site_id' => $data->site_id,
                       'product_application' => $data->product_application
                    ],
                );
            }
        }
    }

    public function saveMaterials($requests)
    {
        # code...
    }

    
}
