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
        'serial_number',
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
        if($files) {
            foreach($files as $file) {
                $original_name = $file->getClientOriginalName();
                $file_size = $file->getSize();
                $file_path = $file->move('uploads/media/advertisements/materials/', str_replace(' ','-', $original_name));    
            }
        }

        if(count($materials) > 0) {
            foreach($materials as $index => $material) {
                $material_data = AdvertisementMaterial::find($material->id);

                $file_path = 'uploads/media/advertisements/materials/';

                $data = [
                    'advertisement_id' => $this->id,
                    'file_type' => $material->file_type,
                    'file_size' => $material->size,
                    'dimension' => $material->width.'x'.$material->height,
                    'width' => $material->width,
                    'height' => $material->height
                ];

                if($material_data) {
                    $data['file_path'] = ($material->name) ? $file_path.$material->name : $material_data->file_path;
                    $material_data->update($data);
                }
                else {
                    $data['file_path'] = ($material->name) ? $file_path.$material->name : '';
                    $material_data = AdvertisementMaterial::create($data);
                }

                $material_data->saveScreens($material->screen_ids, $this->id);
            }
        }
    }

    
}
