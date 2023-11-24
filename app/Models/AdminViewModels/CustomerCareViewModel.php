<?php

namespace App\Models\AdminViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Concern;
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
        'transaction_status',
        'concern_name',
        'user_details',
        'concern_details',

    ];

    public function getUser()
    {   
        $user = UserViewModel::find($this->user_id);
        if($user)
            return $user;
        return null;
    }
    
    public function getConcern()
    {   
        $concern = ConcernViewModel::find($this->concern_id);
        if($concern)
            return $concern;
        return null;
    }

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
        if ($ad_details)
            return $ad_details;
        return null;
    }

    public function getTransactionStatusAttribute()
    {
        $ad_details = $this->getAdvertisementDetails();
        if ($ad_details)
            return $ad_details->transaction_status;
        return null;
    }

    public function getStatusDetailsAttribute()
    {
        $status = $this->getTransactionStatus();
        if ($status)
            return $status;
        return null;
    }

    public function getConcernNameAttribute()
    {
        return Concern::find($this->concern_id)->name;
    }

    public function getUserDetailsAttribute()
    {
        return $this->getUser(); 
    }

    public function getConcernDetailsAttribute()
    {
        return $this->getConcern(); 
    }
}
