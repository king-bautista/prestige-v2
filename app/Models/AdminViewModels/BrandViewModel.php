<?php

namespace App\Models\AdminViewModels;

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
        'logo_image_path',
        'thumbnail_path',
        'brand_details',
        'category_name',
        'main_category_name',
        'supplemental_ids',
        'supplemental_names',
        'tag_ids',
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

    public function getCompany()
    {
        return $this->hasOne('App\Models\ViewModels\CompanyBrands', 'brand_id', 'company_id');
    }

    /****************************************
     *           ATTRIBUTES PARTS            *
     ****************************************/
    public function getLogoImagePathAttribute()
    {
        if ($this->logo)
            return asset($this->logo);
        return asset('/images/no-image-available.png');
    }

    public function getThumbnailPathAttribute()
    {
        if ($this->thumbnail)
            return asset($this->thumbnail);
        return asset('/images/no-image-available.png');
    }

    public function getBrandDetailsAttribute()
    {
        $category = Category::find($this->category_id);
        $parent_category = '';
        if ($category)
            $parent_category = Category::find($category->parent_id);

        $supplemental_ids = $this->getSupplementals()->pluck('supplemental_id');
        $supplementals = Category::whereIn('id', $supplemental_ids)->get();

        $tag_ids = $this->getTags()->pluck('tag_id')->toArray();
        $tags = Tag::whereIn('id', $tag_ids)->get();

        return [
            'category_name' => ($category) ? $category->name : null,
            'parent_category_id' => ($parent_category) ? $category->parent_id : null,
            'parent_category_name' => ($parent_category) ? $parent_category->name : null,
            'supplemental_ids' => ($supplementals) ? $supplemental_ids : null,
            'supplementals' => ($supplementals) ? $supplementals : null,
            //'tag_ids' => (count($tag_ids) > 0) ? implode(",",$tag_ids) : null,
            'tags' => ($tags) ? $tags : null
        ];
    }

    public function getCategoryNameAttribute()
    {
        return $this->brand_details['category_name'];
    }

    public function getMainCategoryNameAttribute()
    {
        return $this->brand_details['parent_category_name'];
    }

    public function getSupplementalIdsAttribute()
    {
        if ($this->brand_details['supplementals']) {
            $supplementals = $this->brand_details['supplementals']->pluck('id')->toArray();
            return implode(', ', $supplementals);
        }
        return null;
    }

    public function getSupplementalNamesAttribute()
    {
        if ($this->brand_details['supplementals']) {
            $supplementals = $this->brand_details['supplementals']->pluck('name')->toArray();
            return implode(', ', $supplementals);
        }
        return null;
    }

    public function getTagIdsAttribute()
    {
        if ($this->brand_details['tags']) {
            $tags = $this->brand_details['tags']->pluck('id')->toArray();
            return implode(', ', $tags);
        }
        return null;
    }

    public function getTagNamesAttribute()
    {
        if ($this->brand_details['tags']) {
            $tags = $this->brand_details['tags']->pluck('name')->toArray();
            return implode(', ', $tags);
        }
        return null;
    }

    public function getTagsAttribute()
    {
        if ($this->brand_details['tags']) {
            return $this->brand_details['tags'];
        }
        return null;
    }
}
