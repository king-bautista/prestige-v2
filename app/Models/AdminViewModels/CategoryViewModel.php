<?php

namespace App\Models\AdminViewModels;

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
        'label',
        'parent_category',
        'supplemental_category_name',
        'children',
    ];

    public function getChildCategories()
    {   
        return $this->hasMany('App\Models\AdminViewModels\CategoryViewModel', 'parent_id', 'id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/    
    public function getLabelAttribute() 
    {
        return $this->name;
    }

    public function getParentCategoryAttribute() 
    {
        $parent_category = Category::find($this->parent_id);
        if($parent_category)
            return $parent_category['name'];
        return null;
    }

    public function getSupplementalCategoryNameAttribute() 
    {
        $supplemental_category = Category::find($this->supplemental_category_id);
        if($supplemental_category)
            return $supplemental_category['name'];

        $parent_supplimental = Category::find($this->parent_id);
        if($parent_supplimental) {
            $supplemental_category = Category::find($parent_supplimental->supplemental_category_id);
            if($supplemental_category)
                return $supplemental_category['name'];
        }
        
        return null;
    }

    public function getChildrenAttribute() 
    {
        $child_categories = $this->getChildCategories()->get();
        if($child_categories)
            return $child_categories;
        return null;
    }

}
