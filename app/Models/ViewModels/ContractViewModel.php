<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\ContractBrand;
use App\Models\ContractScreen;

class ContractViewModel extends Model
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
    protected $table = 'contracts';

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
        'brands',
        'screens',
        'screen_locations',
    ]; 

    public function getBrands()
    {   
        return $this->hasMany('App\Models\ContractBrand', 'contract_id', 'id');
    }

    public function getScreens()
    {   
        return $this->hasMany('App\Models\ContractScreen', 'contract_id', 'id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/   
    public function getBrandsAttribute() 
    {
        $ids = $this->getBrands()->pluck('brand_id');
        return BrandViewModel::whereIn('id', $ids)->get();
    }

    public function getScreensAttribute() 
    {
        $ids = $this->getScreens()->pluck('site_screen_id');
        return SiteScreenViewModel::whereIn('id', $ids)->get();
    }

    public function getScreenLocationsAttribute() 
    {
        $screens = $this->screens->pluck('site_screen_location')->toArray();
            return implode(', ', $screens);
        return null;
    }
}
