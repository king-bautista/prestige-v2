<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use App\Models\Brand;
// use App\Models\Company;
// use App\Models\Category;
 use App\Models\TransactionStatus;

class CustomerCareViewModel extends Model
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
    protected $table = 'customer_care';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $appends = [
        'advertisement_details',
        'status_details',
        'transaction_status'
    ];

    public function getAdvertisementDetails()
    {   
        return AdvertisementViewModel::find($this->advertisement_id);
    }

    public function getTransactionStatus()
    {
        return TransactionStatus::find($this->status_id);
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/

    public function getAdvertisementDetailsAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details;
        return null;
    }

    public function getTransactionStatusAttribute() 
    {
        $ad_details = $this->getAdvertisementDetails();
        if($ad_details)
            return $ad_details->transaction_status;
        return null;
    } 

    public function getStatusDetailsAttribute()
    {
        $status = $this->getTransactionStatus();
        if($status)
            return $status;
        return null;
    }

}