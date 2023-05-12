<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'contract_id',
        'brand_id',
        'status_id',
        'display_duration',
        'name',
        'active',
    ];

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

    public function saveMaterials($materials, $files)
    {
        if(count($materials) > 0) {
            foreach($materials as $index => $material) {
                $file_path = '';
                $file_size = '';
                $original_name = '';

                $original_name = $files[$index]->getClientOriginalName();
                $file_size = $files[$index]->getSize();
                $file_path = $files[$index]->move('uploads/media/advertisements/materials/', str_replace(' ','-', $original_name));

                $data = [
                    'advertisement_id' => $this->id,
                    'file_path' => $file_path,
                    'file_type' => $material->file_type,
                    'file_size' => $file_size,
                    'dimension' => $material->width.'x'.$material->height,
                    'width' => $material->width,
                    'height' => $material->height
                ];

                $material_data = AdvertisementMaterial::find($material->id);
                if($material_data) {
                    $material_data->update($data);
                }
                else {
                    $material_data = AdvertisementMaterial::create($data);
                }

                //$material_data->saveScreens($material->screen_ids);
            }
        }
    }

    
}
