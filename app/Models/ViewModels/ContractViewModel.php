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
        $site_screens = SiteScreenViewModel::whereIn('id', $ids)->get();

        $site_ids = $this->getScreens()->pluck('site_id');
        if($site_ids) {
            $site_all_directory = SiteScreenViewModel::where('site_id', $site_ids)->where('product_application', 'Directory')->groupBy('site_id')->get();
            foreach($site_all_directory as $directory) {
                $site_screens[] = [
                    'id' => 0,
                    'site_id' => $directory->site_id,
                    'site_screen_location' => $directory->site_name.' - All ('.$directory->product_application.')'
                ];
            }
        }

        if($site_screens)
            return $site_screens;
        return null;
    }

    public function getScreenLocationsAttribute() 
    {
        $screens = $this->screens->pluck('site_screen_location')->toArray();
            return implode(', ', $screens);
        return null;
    }
}
