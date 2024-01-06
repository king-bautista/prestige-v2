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
use App\Models\Concern;

class ConcernsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            
            if ($row['name']) {
                $module = Concern::updateOrCreate(
                    [
                        'name' => $row['name'],
                        'description' => $row['description'],
                    ],
                    [
                        'active' => ($row['active'] == 1)? 1:0,
                    ],
                );
            }
        }
    }

    public function link($link = '')
    {
        if ($link != '#') {
            return str_replace((empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]", "", $link);
        }
        return '#';
    }
}
