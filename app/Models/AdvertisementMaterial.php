<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvertisementMaterial extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'advertisement_id',
        'file_path',
        'file_type',
        'file_size',
        'dimension',
        'width',
        'height',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'advertisement_materials';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
