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

use App\Models\SiteBuilding;

class BuildingsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $site_id = session()->get('site_id');
        foreach ($rows as $row) {
            if ($site_id == $row['site_id']) {
                $building = SiteBuilding::updateOrCreate(
                    [
                        'site_id' => $row['site_id'],
                        'name' => $row['name'],
                        'descriptions' => $row['descriptions']
                    ],
                    [
                        'active' => ($row['active'] == 1) ? 1 : 0,
                    ]
                );
            }
        }
    }
}
