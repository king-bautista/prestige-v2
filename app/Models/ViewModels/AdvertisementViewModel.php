<?php

namespace App\Models\ViewModels;

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
        'material_image_path',
        'company_name',
        'company_details',
        'contract_details',
        'brand_name',
        'brand_details',
        'category_id',
        'category_name',
        'parent_category_id',
        'parent_category_name',
        'transaction_status',
        'materials',
    ]; 

    public function getCompany()
    {   
        $company = CompanyViewModel::find($this->company_id);
        if($company)
            return $company;
        return null;
    }

    public function getBrand()
    {   
        $brand = Brand::find($this->brand_id);
        if($brand)
            return $brand;
        return null;
    }

    public function getTransactionStatus()
    {   
        $transaction_status = TransactionStatus::find($this->status_id);
        if($transaction_status)
            return $transaction_status;
        return null;
    }

    public function getCategory()
    {   
        $brand = Brand::find($this->brand_id);
        if(!$brand)
            return null;

        $category = Category::find($brand->category_id);
        if($category)
            return $category;
        return null;
    }

    public function getParentCategory()
    {   
        $category = $this->getCategory();
        if(!$category)
            return null;

        $parent_category = Category::find($category->parent_id);
        if($parent_category)
            return $parent_category;
        return null;
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getMaterialImagePathAttribute()
    {
        // if($this->file_path)
        //     return asset($this->file_path);
        return asset('/images/no-image-available.png');
    } 

    public function getCompanyNameAttribute()
    {
        if($this->getCompany())
            return $this->getCompany()->name; 
        return null;
    }

    public function getCompanyDetailsAttribute()
    {
        return $this->getCompany(); 
    }

    public function getBrandNameAttribute()
    {
        if($this->getBrand())
            return $this->getBrand()->name; 
        return null;
    }

    public function getBrandDetailsAttribute()
    {
        return $this->getBrand(); 
    }

    public function getCategoryIdAttribute()
    {
        if($this->getCategory())
            return $this->getCategory()->id;
        return null; 
    }

    public function getCategoryNameAttribute()
    {
        if($this->getCategory())
            return $this->getCategory()->name;
        return null; 
    }

    public function getParentCategoryIdAttribute()
    {
        if($this->getParentCategory())
            return $this->getParentCategory()->id;
        return null; 
    }

    public function getParentCategoryNameAttribute()
    {
        if($this->getParentCategory())
            return $this->getParentCategory()->name;
        return null;
    }

    public function getTransactionStatusAttribute()
    {
        if($this->getTransactionStatus())
            return $this->getTransactionStatus();
        return null;
    }

    public function getContractDetailsAttribute() 
    {
        $contract = ContractViewModel::find($this->contract_id);
        if($contract)
            return $contract;
        return null;
    }

    public function getMaterialsAttribute() 
    {
        $materials = AdvertisementMaterialViewModel::where('advertisement_id', $this->id)->get();
        if($materials)
            return $materials;
        return null;
    }

}