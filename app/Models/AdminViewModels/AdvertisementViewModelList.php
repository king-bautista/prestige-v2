<?php

namespace App\Models\AdminViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Brand;
use App\Models\Company;
use App\Models\Category;
use App\Models\TransactionStatus;

class AdvertisementViewModelList extends Model
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
    protected $table = 'advertisements';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $appends = [
         'materials',
         'material_thumbnails_path',
    ]; 

    function getAdvertisementMaterials()
    {
        $materials = AdvertisementMaterialViewModel::where('advertisement_id', $this->id)->latest()->get();    
        if($materials)
            return $materials;
        return null;
    }
   
    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getMaterialsAttribute()
    {
        return $this->getAdvertisementMaterials();
    } 

    public function getMaterialThumbnailsPathAttribute()
    {
        if(count($this->materials) > 0)
            return asset($this->materials[0]->thumbnail_path);
        return asset('/images/no-image-available.png');
    }


}
