<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;

class CategoryViewModel extends Model
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
    protected $table = 'categories';

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
        'kiosk_image_primary_path',
        'kiosk_image_top_path',
        'online_image_primary_path',
        'online_image_top_path',
        'parent_category',
        'children',
        'label',
    ];    

    public function getChildCategories()
    {   
        return $this->hasMany('App\Models\ViewModels\CategoryViewModel', 'parent_id', 'id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getKioskImagePrimaryPathAttribute()
    {
        if($this->kiosk_image_primary)
            return asset($this->kiosk_image_primary);
        return null;
    }  

    public function getKioskImageTopPathAttribute()
    {
        if($this->kiosk_image_top)
            return asset($this->kiosk_image_top);
        return null;
    }  

    public function getOnlineImagePrimaryPathAttribute()
    {
        if($this->online_image_primary)
            return asset($this->online_image_primary);
        return null;
    }  

    public function getOnlineImageTopPathAttribute()
    {
        if($this->online_image_top)
            return asset($this->online_image_top);
        return null;
    }

    public function getParentCategoryAttribute() 
    {

        $parent_category = Category::find($this->parent_id);
        if($parent_category)
            return $parent_category['name'];
        return null;
    }

    public function getChildrenAttribute() 
    {

        $child_categories = $this->getChildCategories()->get();
        if($child_categories)
            return $child_categories;
        return null;
    }

    public function getLabelAttribute() 
    {
        return $this->name;
    }
}
