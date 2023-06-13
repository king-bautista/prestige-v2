<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;
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
        'category_name',
        'supplemental_names',
        'tag_names',
    ]; 

    public function getSupplementals()
    {   
        return $this->hasMany('App\Models\BrandSupplemental', 'brand_id', 'id');
    }

    public function getTags()
    {   
        return $this->hasMany('App\Models\BrandTag', 'brand_id', 'id');
    }

    public function getCategory()
    {   
        return $this->hasMany('App\Models\Category', 'id', 'category_id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getSupplementalsAttribute()
    {
        $ids = $this->getSupplementals()->pluck('supplemental_id');
        return Category::whereIn('id', $ids)->get();
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
        return asset('/images/no-image-available.png');
    } 

    public function getCategoryNameAttribute()
    {
        $category = $this->getCategory()->first();
        if($category)
            return $category['name'];
        return null;
    } 

    public function getSupplementalNamesAttribute()
    {
        $ids = $this->getSupplementals()->pluck('supplemental_id');
        if($ids) {
            $supplementals = Category::whereIn('id', $ids)->pluck('name')->toArray();
            return implode(', ', $supplementals);
        }
        return null;
    } 

    public function getTagNamesAttribute()
    {
        $ids = $this->getTags()->pluck('tag_id');
        if($ids) {
            $tags = Tag::whereIn('id', $ids)->pluck('name')->toArray();
            return implode(', ', $tags);
        }
        return null;
    } 

}
