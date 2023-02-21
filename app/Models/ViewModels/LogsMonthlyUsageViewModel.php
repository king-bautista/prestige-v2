<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Log;

class LogsMonthlyUsageViewModel extends Model
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
    protected $table = 'logs';

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
        'jan_count', 
        'feb_count', 
        'mar_count', 
        'apr_count', 
        'may_count', 
        'jun_count', 
        'jul_count', 
        'aug_count', 
        'sep_count', 
        'oct_count', 
        'nov_count', 
        'dec_count', 
        'ave_count', 
    ];

    static $current_site_id = null;
    static $current_year = null;

    static function setSiteId($id=null, $year=null)
    {
        self::$current_site_id = $id;
        self::$current_year = $year;
    }

    public function getJanCountAttribute() 
    {
        $site_id = self::$current_site_id;
        $current_year = self::$current_year;

        return Log::whereMonth('created_at', 1)->where('page', $this->page)
        ->when($site_id, function($query) use ($site_id){
            return $query->where('site_id', $site_id);
        })
        ->whereYear('created_at', $current_year)
        ->get()->count();
    }

    public function getFebCountAttribute() 
    {
        $site_id = self::$current_site_id;
        $current_year = self::$current_year;

        return Log::whereMonth('created_at', 2)->where('page', $this->page)
        ->when($site_id, function($query) use ($site_id){
            return $query->where('site_id', $site_id);
        })
        ->whereYear('created_at', $current_year)
        ->get()->count();
    }

    public function getMarCountAttribute() 
    {
        $site_id = self::$current_site_id;
        $current_year = self::$current_year;

        return Log::whereMonth('created_at', 3)->where('page', $this->page)
        ->when(self::$current_site_id, function($query) use ($site_id){
            return $query->where('site_id', $site_id);
        })
        ->whereYear('created_at', $current_year)
        ->get()->count();
    }

    public function getAprCountAttribute() 
    {
        $site_id = self::$current_site_id;
        $current_year = self::$current_year;

        return Log::whereMonth('created_at', 4)->where('page', $this->page)
        ->when(self::$current_site_id, function($query) use ($site_id){
            return $query->where('site_id', $site_id);
        })
        ->whereYear('created_at', $current_year)
        ->get()->count();
    }

    public function getMayCountAttribute() 
    {
        $site_id = self::$current_site_id;
        $current_year = self::$current_year;

        return Log::whereMonth('created_at', 5)->where('page', $this->page)
        ->when(self::$current_site_id, function($query) use ($site_id){
            return $query->where('site_id', $site_id);
        })
        ->whereYear('created_at', $current_year)
        ->get()->count();
    }

    public function getJunCountAttribute() 
    {
        $site_id = self::$current_site_id;
        $current_year = self::$current_year;

        return Log::whereMonth('created_at', 6)->where('page', $this->page)
        ->when(self::$current_site_id, function($query) use ($site_id){
            return $query->where('site_id', $site_id);
        })
        ->whereYear('created_at', $current_year)
        ->get()->count();
    }

    public function getJulCountAttribute() 
    {
        $site_id = self::$current_site_id;
        $current_year = self::$current_year;

        return Log::whereMonth('created_at', 7)->where('page', $this->page)
        ->when(self::$current_site_id, function($query) use ($site_id){
            return $query->where('site_id', $site_id);
        })
        ->whereYear('created_at', $current_year)
        ->get()->count();
    }

    public function getAugCountAttribute() 
    {
        $site_id = self::$current_site_id;
        $current_year = self::$current_year;

        return Log::whereMonth('created_at', 8)->where('page', $this->page)
        ->when(self::$current_site_id, function($query) use ($site_id){
            return $query->where('site_id', $site_id);
        })
        ->whereYear('created_at', $current_year)
        ->get()->count();
    }

    public function getSepCountAttribute() 
    {
        $site_id = self::$current_site_id;
        $current_year = self::$current_year;

        return Log::whereMonth('created_at', 9)->where('page', $this->page)
        ->when(self::$current_site_id, function($query) use ($site_id){
            return $query->where('site_id', $site_id);
        })
        ->whereYear('created_at', $current_year)
        ->get()->count();
    }

    public function getOctCountAttribute() 
    {
        $site_id = self::$current_site_id;
        $current_year = self::$current_year;

        return Log::whereMonth('created_at', 10)->where('page', $this->page)
        ->when(self::$current_site_id, function($query) use ($site_id){
            return $query->where('site_id', $site_id);
        })
        ->whereYear('created_at', $current_year)
        ->get()->count();
    }

    public function getNovCountAttribute() 
    {
        $site_id = self::$current_site_id;
        $current_year = self::$current_year;

        return Log::whereMonth('created_at', 11)->where('page', $this->page)
        ->when(self::$current_site_id, function($query) use ($site_id){
            return $query->where('site_id', $site_id);
        })
        ->whereYear('created_at', $current_year)
        ->get()->count();
    }

    public function getDecCountAttribute() 
    {
        $site_id = self::$current_site_id;
        $current_year = self::$current_year;

        return Log::whereMonth('created_at', 12)->where('page', $this->page)
        ->when(self::$current_site_id, function($query) use ($site_id){
            return $query->where('site_id', $site_id);
        })
        ->whereYear('created_at', $current_year)
        ->get()->count();
    }

    public function getAveCountAttribute() 
    {
        $site_id = self::$current_site_id;
        $current_year = self::$current_year;

        $month_count = Log::selectRaw('MONTH(created_at) as months')
        ->when(self::$current_site_id, function($query) use ($site_id){
            return $query->where('site_id', $site_id);
        })->groupBy('months')->get()->count();
        return round($this->total_count/$month_count, 2);
    }
    
}
