<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Thumbnails;
use Image;
use VideoThumbnail;

class Advertisement extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'serial_number',
        'company_id',
        'contract_id',
        'brand_id',
        'display_duration',
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
                $filename = pathinfo($original_name, PATHINFO_FILENAME);
                $mime_type = explode("/", $file->getClientMimeType());
                $file_type = $mime_type[0];
                $file_size = $file->getSize();
                $file_path = $file->move('uploads/media/advertisements/materials/', str_replace(' ','-', $original_name)); 
                $image_size = getimagesize($file_path);

                $required_size = 150;
                $new_width = 0;
                $new_height = 0;

                if($file_type == 'image') {
                    $width = $image_size[0];
                    $height = $image_size[1];

                    $aspect_ratio = $width/$height;
                    if ($aspect_ratio >= 1.0) {
                        $new_width = $required_size;
                        $new_height = $required_size / $aspect_ratio;
                    } else {
                        $new_width = $required_size * $aspect_ratio;
                        $new_height = $required_size;
                    }

                    $thumbnails_path = public_path('uploads/media/advertisements/materials/thumbnails/');
                    $img = Image::make($file_path);
                    $img->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($thumbnails_path.str_replace(' ','-', $original_name));
                }                
            }
        }

        if(count($materials) > 0) {
            foreach($materials as $index => $material) {
                $material_data = AdvertisementMaterial::find($material->id);

                $required_size = 150;
                $new_width = 0;
                $new_height = 0;

                $file_path = 'uploads/media/advertisements/materials/';
                $thumbnails_path = 'uploads/media/advertisements/materials/thumbnails/';

                if(!$material->file_type)
                    continue;

                $data = [
                    'advertisement_id' => $this->id,
                    'file_type' => $material->file_type,
                    'file_size' => $material->size,
                    'dimension' => $material->width.'x'.$material->height,
                    'width' => $material->width,
                    'height' => $material->height
                ];

                $thumbnails_name = $material->name;
                if($material->file_type == 'video') {
                    $thumbnails_name = str_replace(".ogv",".jpg",$material->name);

                    $width = $material->width;
                    $height = $material->height;

                    $aspect_ratio = $width/$height;
                    if ($aspect_ratio >= 1.0) {
                        $new_width = $required_size;
                        $new_height = $required_size / $aspect_ratio;
                    } else {
                        $new_width = $required_size * $aspect_ratio;
                        $new_height = $required_size;
                    }

                    VideoThumbnail::createThumbnail(
                        public_path($file_path.$material->name),
                        public_path('uploads/media/advertisements/materials/thumbnails'),
                        $thumbnails_name,
                        2,
                        $new_width,
                        $new_height
                    );
                }

                if($material_data) {
                    $data['file_path'] = ($material->name) ? $file_path.$material->name : $material_data->file_path;
                    $data['thumbnail_path'] = ($material->name) ? $thumbnails_path.$thumbnails_name : $material_data->thumbnail_path;
                    $material_data->update($data);
                }
                else {
                    $data['file_path'] = ($material->name) ? $file_path.$material->name : '';
                    $data['thumbnail_path'] = ($material->name) ? $thumbnails_path.$thumbnails_name : '';
                    $material_data = AdvertisementMaterial::create($data);
                }
            }
        }
    }

    
}
