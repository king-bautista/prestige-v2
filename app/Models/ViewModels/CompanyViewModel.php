<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Company;
use App\Models\Brand;
use App\Models\Classification;

class CompanyViewModel extends Model
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
    protected $table = 'companies';

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
        'parent_company',
        'classification_name',
        'label',
        'brands',
        'contracts',
    ]; 

    public function getBrands()
    {   
        return $this->hasMany('App\Models\CompanyBrands', 'company_id', 'id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/    
    public function getParentCompanyAttribute() 
    {
        $parent_company = Company::find($this->parent_id);
        if($parent_company)
            return $parent_company['name'];
        return null;
    }

    public function getClassificationNameAttribute() 
    {

        $classification = Classification::find($this->classification_id);
        if($classification)
            return $classification['name'];
        return null;
    }

    public function getLabelAttribute() 
    {
        return $this->name;
    }

    public function getBrandsAttribute() 
    {
        $ids = $this->getBrands()->pluck('brand_id');
        return BrandViewModel::whereIn('id', $ids)->get();
    }

    public function getContractsAttribute() 
    {
        return ContractViewModel::where('company_id', $this->id)->get();
    }
}