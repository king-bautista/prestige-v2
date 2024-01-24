<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;

use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Storage;

use App\Models\BrandProductPromos;
use App\Models\Tag;

class BrandProductsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $brand_id = session()->get('brand_id');
        foreach ($rows as $row) {
            if ($brand_id == $row['brand_id']) {
                if ($row['name']) {
                    $brand = BrandProductPromos::updateOrCreate(
                        [
                            'name' => $row['name']
                        ],
                        [
                            'descriptions' => $row['descriptions'],
                            'type' => $row['type'],
                            'thumbnail' => ($row['thumbnail']) ? $this->uploadBanner($row['thumbnail']) : null,
                            'image_url' => ($row['image_url']) ? $this->uploadBanner($row['image_url']) : null,
                        ]
                    );
                }
            }
        }
    }


    public function uploadBanner($logo = '')
    {
        if ($logo) {
            if (file_get_contents(public_path() . '/' . $logo))
                return $logo;
            // $contents = file_get_contents($logo);   
            // $name = str_replace(' ','-',substr($logo, strrpos($logo, '/') + 1));
            // if(Storage::disk('brand')->put($name, $contents))
            //     return 'uploads/media/brand/'.$name;
            return null;
        }
        return null;
    }
}
