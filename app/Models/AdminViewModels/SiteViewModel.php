<?php

namespace App\Models\AdminViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use App\Models\Company;
use Str;

class SiteViewModel extends Model
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
    protected $table = 'sites';

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
        'descriptions_ellipsis',
        'details',
        'short_code',
        'site_logo_path',
        'site_banner_path',
        'site_background_path',
        'site_background_portrait_path',
        'multilanguage',
        'company_id',
        'property_owner',
        'operational_hours',
    ];

    public function getSiteDetails()
    {   
        return $this->hasMany('App\Models\SiteMeta', 'site_id', 'id');
    }

    function getTodaySchedule($json_data) {
        if($json_data) {
            return [
                'is_open' => (strtotime($json_data->start_time) <= time() && strtotime($json_data->end_time) >= time()) ? 1 : 0,
                'start_time' => date("h:i a",strtotime($json_data->start_time)),
                'end_time' => date("h:i a",strtotime($json_data->end_time)),
            ];
        }
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getDetailsAttribute() 
    {
        return $this->getSiteDetails()->pluck('meta_value','meta_key')->toArray();
    }

    public function getSiteLogoPathAttribute()
    {
        if($this->site_logo)
            return asset($this->site_logo);
        return asset('/images/no-image-available.png');
    }  

    public function getSiteBannerPathAttribute()
    {
        if($this->site_banner)
            return asset($this->site_banner);
        return asset('/images/no-image-available.png');
    }  

    public function getSiteBackgroundPathAttribute()
    {
        if($this->site_background)
            return asset($this->site_background);
        return asset('/images/no-image-available.png');
    } 

    public function getSiteBackgroundPortraitPathAttribute()
    {
        if($this->site_background_portrait)
            return asset($this->site_background_portrait);
        return asset('/images/no-image-available.png');
    } 

    public function getDescriptionsEllipsisAttribute()
    {
        if($this->descriptions)
            return Str::limit($this->descriptions, 95);
        return null;
    }
    
    public function getShortCodeAttribute()
    {
        $site_details = $this->getSiteDetails()->where('meta_key', 'site_code')->first();
        if($site_details)
            return $site_details->meta_value;
        return null;
    }

    public function getIsPremiereAttribute()
    {
        $site_details = $this->getSiteDetails()->where('meta_key', 'is_premiere')->first();
        if($site_details)
            return $site_details->meta_value;
        return 0;
    }

    public function getMultilanguageAttribute()
    {
        $site_details = $this->getSiteDetails()->where('meta_key', 'multilanguage')->first();
        if($site_details)
            return $site_details->meta_value;
        return 0;
    }

    public function getCompanyIdAttribute()
    {
        $site_details = $this->getSiteDetails()->where('meta_key', 'company_id')->first();
        if($site_details)
            return $site_details->meta_value;
        return null;
    }

    public function getPropertyOwnerAttribute()
    {
        $company_details = Company::find($this->company_id);
        if($company_details)
            return $company_details->name;
        return null;
    }

    public function getOperationalHoursAttribute() 
    {
        $current_day = Carbon::now()->isoFormat('ddd');
        $new_schedule = [];        
        if(isset($this->details['schedules'])) {
            $schedules = $this->details['schedules'];
            $json_data = json_decode($schedules);
            
            if(count($json_data) > 0) {
                foreach($json_data as $data) {
                    if(strpos($data->schedules, $current_day) !== false) {
                        return $this->getTodaySchedule($data);
                    }
                }
            }
            else {
                return $this->getTodaySchedule($json_data);
            }            
        }
        return null;
    }
    
}
