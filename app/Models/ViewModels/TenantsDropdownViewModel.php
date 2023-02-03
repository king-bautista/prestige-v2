<?php

namespace App\Models\ViewModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Site;
use App\Models\Brand;

class TenantsDropdownViewModel extends Model
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
    protected $table = 'site_tenants';

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
        'brand_site_name',
    ];

    public function getBrandSiteNameAttribute() 
    {
        $brand_name = Brand::find($this->brand_id)->name;
        $site_name = Site::find($this->site_id)->name;
        return $site_name.' - '.$brand_name;
    }
}
