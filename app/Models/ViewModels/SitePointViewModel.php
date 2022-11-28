<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;

use App\Models\SiteTenant;
use App\Models\Brand;
use App\Models\Amenity;

class SitePointViewModel extends Model
{
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
    protected $table = 'site_points';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Append additiona info to the return data
     *
     * @var string
     */
	public $appends = [
        'brand_name',
        'amenity_name',
    ];

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getBrandNameAttribute() 
    {
        if($this->tenant_id) {
            $site_tenant = SiteTenant::find($this->tenant_id);
            return Brand::find($site_tenant->brand_id)->name;
        }
        return null;
    }

    public function getAmenityNameAttribute() 
    {
        if($this->point_type) 
            return Amenity::find($this->point_type)->name;
        return null;
    }
}
