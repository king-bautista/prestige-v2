<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvertisementMaterialViewModel extends Model
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
    protected $table = 'advertisement_materials';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $appends = [
        'material_path',
        'pi_screens',
    ]; 

    public function getScreens()
    {   
        return $this->hasMany('App\Models\AdvertisementScreen', 'material_id', 'id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getMaterialPathAttribute()
    {
        if($this->file_path)
            return asset($this->file_path);
        return asset('/images/no-image-available.png');
    } 

    public function getPiScreensAttribute()
    {
        $ids = $this->getScreens()->pluck('pi_product_id');
        $site_screen_products = PiProductViewModel::whereIn('id', $ids)->get();

        if($site_screen_products)
            return $site_screen_products;
        return null;
    } 

}
