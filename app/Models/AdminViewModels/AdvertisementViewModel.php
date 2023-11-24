<?php

namespace App\Models\AdminViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Brand;
use App\Models\Company;
use App\Models\Category;
use App\Models\TransactionStatus;

class AdvertisementViewModel extends Model
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
    protected $table = 'advertisements';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $appends = [
        'materials',
        'material_thumbnails_path',
        'material_image_path',        
        'company_details',
        'company_name',
        'brand_details',
        'brand_name',
        'contract_details',
        'transaction_status',
        'category_details',
    ]; 

    function getAdvertisementMaterials()
    {
        $materials = AdvertisementMaterialViewModel::where('advertisement_id', $this->id)->latest()->get();    
        if($materials)
            return $materials;
        return null;
    }

    public function getCompanyDetails()
    {   
        $company = CompanyViewModel::find($this->company_id);
        if($company)
            return $company;
        return null;
    }

    public function getBrandDetails()
    {   
        $brand = Brand::find($this->brand_id);
        if($brand)
            return $brand;
        return null;
    }

    public function getContractDetails()
    {
        $contract = ContractViewModel::find($this->contract_id);
        if($contract)
            return $contract;
        return null;
    }

    public function getTransactionDetails()
    {   
        $transaction_status = TransactionStatus::find($this->status_id);
        if($transaction_status)
            return $transaction_status;
        return null;
    }

    public function getCategoryDetails()
    {   
        $category = Category::find($this->brand_details->category_id);
        $parent_category = Category::find($category->parent_id);
        return [
            'category' => $category,
            'parent_category' => $parent_category
        ];
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getMaterialsAttribute()
    {
        return $this->getAdvertisementMaterials();
    } 

    public function getMaterialImagePathAttribute()
    {
        if(count($this->materials) > 0)
            return asset($this->materials[0]->file_path);
        return asset('/images/no-image-available.png');
    } 

    public function getMaterialThumbnailsPathAttribute()
    {
        if(count($this->materials) > 0)
            return asset($this->materials[0]->thumbnail_path);
        return asset('/images/no-image-available.png');
    }

    public function getCompanyDetailsAttribute()
    {
        return $this->getCompanyDetails(); 
    }

    public function getCompanyNameAttribute()
    {
        if($this->company_details)
            return $this->company_details->name; 
        return null;
    }

    public function getBrandDetailsAttribute()
    {
        return $this->getBrandDetails(); 
    }

    public function getBrandNameAttribute()
    {
        if($this->brand_details)
            return $this->brand_details->name; 
        return null;
    }

    public function getContractDetailsAttribute() 
    {
        $contract = $this->getContractDetails();
        if($contract)
            return $contract;
        return null;
    }

    public function getTransactionStatusAttribute()
    {
        if($this->getTransactionDetails())
            return $this->getTransactionDetails();
        return null;
    }

    public function getCategoryDetailsAttribute()
    {
        if($this->getCategoryDetails())
            return $this->getCategoryDetails();
        return null;
    }

}
