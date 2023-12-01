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

use App\Models\Amenity;

class AmenitiesImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
    
            if($row['name']) { 
                $brand = Amenity::updateOrCreate(
                    [
                        'name' => $row['name']
                    ],
                    [
                        'icon' => ($row['icon']) ? $this->uploadIcon($row['icon']) : null
                    ]
                );
            }
	    }

    }

    public function uploadIcon($icon = '')
    {   
        if($icon){ 
            if(file_exists(public_path().'/'.$icon))
                return $icon;
            return null;
        }
        return null;
    }
}
