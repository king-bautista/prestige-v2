<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\Site;
use App\Models\CinemaGenre;

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
    ];

    public function getSiteNameAttribute() 
    {
        return Site::find($this->site_id)->name;
    }

    public function getGenreNameAttribute() 
    {
        return CinemaGenre::where('genre_code', $this->genre)->first()->genre_label;
    }

}
