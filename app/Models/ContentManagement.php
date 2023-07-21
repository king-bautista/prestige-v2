<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentManagement extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'serial_number',
        'advertisement_id',
        'status_id',
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
    protected $table = 'content_management';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function saveScreens($screens)
    {
        if(count($screens) > 0) {
            $deleted = ContentScreen::where('content_id', $this->id)->delete();

            foreach ($screens as $data) {
                ContentScreen::updateOrCreate(
                    [
                        'content_id' => $this->id,
                        'site_screen_id' => $data['id'],
                        'site_id' => $data['site_id'],
                        'product_application' => $data['product_application'],
                    ],
                );
            }
        }    
    }

}
