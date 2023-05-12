<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvertisementMaterial extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'advertisement_id',
        'file_path',
        'file_type',
        'file_size',
        'dimension',
        'width',
        'height',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'advertisement_materials';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function saveScreens($screens, $advertisement_id)
    {
        if(count($screens) > 0) {
            $deleted = AdvertisementScreen::where('advertisement_id', $advertisement_id)->where('material_id', $this->id)->delete();
            if(!$deleted) 
                return false;

            foreach ($screens as $data) {
                $site_id = '';
                if(isset($data->site_id)) {
                    $site_id = $data->site_id;
                }
                else {
                    $site_id = $data->site_screen_details->site_id;
                }

                AdvertisementScreen::updateOrCreate(
                    [
                       'advertisement_id' => $advertisement_id,
                       'material_id' => $this->id,
                       'site_screen_id' => $data->id,
                       'site_id' => $site_id,
                       'ad_type' => $data->ad_type
                    ],
                );
            }

        }
    }
}
