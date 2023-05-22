<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;

class ContentScreenViewModel extends Model
{
    /**
     * set timestamps to false
     *
     * @var boolean
    */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'content_screens';

    
}
