<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;

class SiteCategoryViewModel extends Model
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
    protected $table = 'company_categories';

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
        'main_category_id',
        'parent_category_id',
        'category_class',
        'category_name',
        'category_type',
        'kiosk_image_primary_path',
        'kiosk_image_top_path',    
    ];

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/ 
    public function getMainCategoryIdAttribute() {
        return $this->category_id;
    }

    public function getParentCategoryIdAttribute() {
        return $this->category_id;
    }

    public function getCategoryNameAttribute() {
        if(!$this->label) {
            if($this->sub_category_id) {
                return Category::find($this->sub_category_id)->name;
            }
            else {
                return Category::find($this->category_id)->name;
            }
        }
        return $this->label;
    }

    public function getCategoryClassAttribute() {
        if($this->sub_category_id) {
            return Category::find($this->sub_category_id)->class_name;
        }
        else {
            return Category::find($this->category_id)->class_name;
        }
        return null;
    }

    public function getCategoryTypeAttribute() {
        if($this->sub_category_id) {
            return Category::find($this->sub_category_id)->category_type;
        }
        else {
            return Category::find($this->category_id)->category_type;
        }
        return null;
    }

    public function getKioskImagePrimaryPathAttribute() {
        if($this->kiosk_image_primary)
            return asset($this->kiosk_image_primary);
        return asset('/images/no-image-available.png');
    }

    public function getKioskImageTopPathAttribute() {
        if($this->kiosk_image_top)
            return asset($this->kiosk_image_top);
        return asset('/images/no-image-available.png');
    }

}
