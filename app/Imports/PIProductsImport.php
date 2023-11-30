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

use App\Models\PiProduct;

class PIProductsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if ($row['product_application'] == 'Digital Signage' || $row['product_application'] == 'Directory' ) {
                $pi_product = PiProduct::updateOrCreate(
                    [
                        'product_application' => $row['product_application'],
                        'ad_type' => $row['ad_type']
                    ],
                    [
                        'descriptions' => $row['descriptions'],
                        'remarks' => $row['remarks'],
                        'sec_slot' => $row['sec_slot'],
                        'slots' => $row['slots'],
                        'is_exclusive' => $row['is_exclusive'],
                        'active' => $row['active'],
                    ]
                );
                
                if(empty($pi_product->serial_number))
                    $pi_product->serial_number = 'PI-'.Str::padLeft($pi_product->id, 5, '0');
                    $pi_product->save();
            }
        }
    }
}