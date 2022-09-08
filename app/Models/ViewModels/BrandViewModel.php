<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Supplemental;
use App\Models\Tag;

class BrandViewModel extends Model
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
    protected $table = 'brands';

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
        'supplementals',
        'tags',
        'logo_image_path',
    ]; 

    public function getSupplementals()
    {   
        return $this->hasMany('App\Models\BrandSupplemental', 'brand_id', 'id');
    }

    public function getTags()
    {   
        return $this->hasMany('App\Models\BrandTag', 'brand_id', 'id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getSupplementalsAttribute()
    {
        $ids = $this->getSupplementals()->pluck('supplemental_id');
        return Supplemental::whereIn('id', $ids)->get();
    }  

    public function getTagsAttribute()
    {
        $ids = $this->getTags()->pluck('tag_id');
        return Tag::whereIn('id', $ids)->get();
    }  

    public function getLogoImagePathAttribute()
    {
        if($this->logo)
            return asset($this->logo);
        return null;
    }  

}
