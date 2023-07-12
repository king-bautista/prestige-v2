<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\SitePoint;

class SitePointLinkViewModel extends Model
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
    protected $table = 'site_point_links';

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
        'point_a_x',
        'point_a_y',
        'point_b_x',
        'point_b_y',
    ];

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getPointAXAttribute() 
    {
        $point_a = SitePoint::find($this->point_a);
        if($point_a)
            return $point_a->point_x;
        return null;
    }

    public function getPointAYAttribute() 
    {
        $point_a = SitePoint::find($this->point_a);
        if($point_a)
            return $point_a->point_y;
        return null;
    }

    public function getPointBXAttribute() 
    {
        $point_b = SitePoint::find($this->point_b);
        if($point_b)
            return $point_b->point_x;
        return null;
    }

    public function getPointBYAttribute() 
    {
        $point_b = SitePoint::find($this->point_b);
        if($point_b)
            return $point_b->point_y;
        return null;
    }
}
