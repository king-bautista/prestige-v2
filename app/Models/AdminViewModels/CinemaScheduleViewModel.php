<?php

namespace App\Models\AdminViewModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\Site;
use App\Models\CinemaGenre;
use App\Models\CinemaSchedule;

use Carbon\Carbon;

class CinemaScheduleViewModel extends Model
{
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
    protected $table = 'cinema_schedules';

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
        'site_name',
        'genre_name',
        'cinema_schedules',
    ];

    public function getSiteNameAttribute() 
    {
        return Site::find($this->site_id)->name;
    }

    public function getGenreNameAttribute() 
    {
        $genre = CinemaGenre::where('genre_code', $this->genre)->first();
        if($genre)
            return $genre->genre_label;
        return null;
    }

    public function getCinemaSchedulesAttribute() 
    {
        $start_date =  date('Y-m-d 00:00:00');
        $end_date =  date('Y-m-d 23:59:59');
        $new_cinema_schedules = [];

        $cinema_schedules = CinemaSchedule::where('film_id', $this->film_id)
        ->where('site_id', $this->site_id)
        ->where('show_time', '>=', $start_date)
        ->where('show_time', '<=', $end_date)
        ->get();

        foreach($cinema_schedules as $index => $schedule) {
            $new_cinema_schedules[$schedule->screen_name][] = Carbon::parse($schedule->show_time)->format('M d, Y h:i A');
        }

        return $new_cinema_schedules;
    }
}
