<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CinemaSchedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'synopsis',
        'opening_date',
        'rating',
        'rating_description',
        'genre',
        'runtime',
        'casting',
        'trailer_url',
        'cinema_id',
        'screen_code',
        'screen_name',
        'film_id',
        'genre2',
        'genre3',
        'cinema_id',
        'show_time',
    ];

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
}
