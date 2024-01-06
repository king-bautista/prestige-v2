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

use App\Models\SiteScreenProduct;

class SiteScreenProductsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if ($row['site_screen_id']) {
                $site_screen_product = SiteScreenProduct::updateOrCreate(
                    [
                        'site_screen_id' => $row['site_screen_id'],
                        'ad_type' => $row['ad_type'],
                        'dimension' => $row['width'] . 'x' . $row['height'],
                        'width' => $row['width'],
                        'height' => $row['height'],
                        'sec_slot' => $row['sec_slot'],
                        'slots' => $row['slots'],
                    ],
                    [
                        'description' => $row['description'],
                        'is_exclusive' => ($row['is_exclusive'] == 1) ? 1 : 0,
                        'active' => ($row['active'] == 1) ? 1 : 0,
                    ]
                );

                if (empty($site_screen_product->serial_number))
                    $site_screen_product->serial_number = 'SSP-' . Str::padLeft($site_screen_product->id, 5, '0');
                $site_screen_product->save();
            }
        }
    }
}
