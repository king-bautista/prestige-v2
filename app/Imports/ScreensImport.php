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

use App\Models\SiteScreen;

class ScreensImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // if ($row['name'] ) {
            //     ($row['site_name']) ? $this->getCategoryId($row['']) : 0,
            //     // $site_screen = SiteScreen::updateOrCreate(
            //     //     [
            //     //         'names' => $row['name'],
            //     //         'screen_type' => $row['screen_type'],
            //     //         'orientation' => $row['orientation'],
            //     //         'product_application' => $row['product_application'],
            //     //         'category_id' => ($row['sub_category']) ? $this->getCategoryId($row['']) : 0,
            //     //         '' => $row[''],
            //     //         '' => $row['']
            //     //     ],
            //     //     [
            //     //         'physical_size_diagonal' => $row['physical_size_diagonal'],
            //     //         'physical_size_width' => $row['physical_size_width'],
            //     //         'physical_size_height' => $row['physical_size_height'],
            //     //         'physical_serial_number' => $row['physical_serial_number'],
            //     //         'active' => $row['active'],
            //     //     ]
            //     // );
                
            //     // if(empty($site_screen->serial_number))
            //     //     $site_screen->serial_number = 'PI-'.Str::padLeft($site_screen->id, 5, '0');
            //     //     $site_screen->save();
            // }
        }
    }
}